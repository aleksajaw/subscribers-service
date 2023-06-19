

<?php

        $query = "SELECT audit_subscribers.subscriber_number AS number,
                        audit_subscribers.subscriber_name AS fname, subscribers.email, audit_subscribers.action_performed,
                        audit_subscribers.action_type, audit_subscribers.date_added
                    FROM audit_subscribers JOIN subscribers ON audit_subscribers.subscriber_number = subscribers.number
                    WHERE action_type IN ('insert', 'update')
                    AND subscriber_number NOT IN (
                        SELECT subscriber_number
                        FROM audit_subscribers
                        WHERE action_type = 'delete'
                    )
                    ORDER BY date_added DESC, number";

        $stmt = $mysqli->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();


    
            require( './inc/classes/userListTableBuilder.php');


            $userListTableBuilder = new userListTableBuilder();


            //echo $userListTableBuilder->userListHeader(['#','Name','Email','Action Performed', 'Date', 'Action']);

            $ignoredHeadings = ['action_type'];

            $headingsInfo = mysqli_fetch_fields($result);
            $headingsInfo = array_filter($headingsInfo, function($obj) use ($ignoredHeadings) {
                return !in_array($obj->name, $ignoredHeadings);
            });
            $headingsArr[] = 'user_action';


            foreach($headingsInfo as $headingInfo) {
                $headingName = $headingInfo->name;
                $headingsArr[] = $headingName;
            }
            $userListTableBuilder->userListHeader($headingsArr);
            
            $prevUserNumber = null;
            $nextRowsForSameUser = ['action_performed', 'date_added'];
            
            $rowClass = '';
            $noDataRowClass = '';

            
            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    $userNumber = isset($row['number']) ? $row['number'] : null;
                    $hasUserNumber = $userNumber && $userNumber === $prevUserNumber;
                    
                    $isRowHidden = $hasUserNumber;
                    $rowClass = ( $isRowHidden ) ? 'user-list__row--hidden-user ' : '';
                    
                    $userActionCell = $userListTableBuilder->userActionCell(['edit'=>$userNumber,'del'=>$userNumber]);
                    
                    /*$userActionCell = ($row['number'] != $prevUserNumber)
                                        ? $userListTableBuilder->userActionCell(['edit'=>$row["number"],'del'=>$row["number"]])
                                        : $userListTableBuilder->userActionCell([]);*/

                    $userColumns = [];

                    foreach($headingsInfo as $column) {
                        $colName = $column->name;
                        $userColumns[$colName] = $row[$colName];
                        /*$userColumns[] = ( $row['number'] != $prevUserNumber || in_array($colName, $nextRowsForSameUser) )
                                            ? $row[$colName]
                                            : '';*/
                    }

                    $userRowCells = array_merge(
                                        [$userActionCell], 
                                        $userListTableBuilder->userDataCells($userColumns)
                                    );


                    if ( isset($row['action_type']) ) $rowClass .= 'user-list__row--' . $row['action_type'];

                    $userListTableBuilder->userDataRow($userNumber, $userRowCells, $rowClass);

                    $prevUserNumber = $userNumber;
                }
                $noDataRowClass = 'display-none';
            }


            $userListTableBuilder->noUserDataRow($noDataRowClass);
        

            $userActionCell = $userListTableBuilder->userActionCell(['add' => true, 'class' => 'user-list__cell--always-visible']);
            $newUserCells = array_merge(
                                [$userActionCell],
                                $userListTableBuilder->newUserDataCells(null)
                            );

            $userListTableBuilder->userDataRow(null, $newUserCells, 'user-list__row--add-user');

        ?>

