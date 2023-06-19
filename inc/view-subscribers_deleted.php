

<?php

        $query = "SELECT subscriber_number AS number, subscriber_name AS fname, action_type, action_performed, date_added
                    FROM audit_subscribers
                    WHERE subscriber_number IN (
                        SELECT subscriber_number
                        FROM audit_subscribers
                        WHERE action_type = 'delete'
                        )
                    ORDER BY
                        subscriber_number,
                        CASE WHEN action_type = 'delete' THEN 0 ELSE 1 END,
                        date_added DESC";


        $stmt = $mysqli->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();



            //echo $userListTableBuilder->userListHeader(['#','Name','Email','Action Performed', 'Date', 'Action']);

            $ignoredHeadings = ['action_type'];

            $headingsInfo = mysqli_fetch_fields($result);
            $headingsInfo = array_filter($headingsInfo, function($obj) use ($ignoredHeadings) {
                return !in_array($obj->name, $ignoredHeadings);
            });
            
            $prevUserNumber = null;
            $nextRowsForSameUser = ['action_performed', 'date_added'];
            
            $rowClass = '';

            
            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    $userNumber = isset($row['number']) ? $row['number'] : null;
                    $hasUserNumber = $userNumber && $userNumber === $prevUserNumber;
                    
                    $isRowHidden = $hasUserNumber;
                    $rowClass = ( $isRowHidden ) ? 'user-list__row--hidden-user ' : '';
                    
                    $userActionCell = $userListTableBuilder->userEmptyCell('user-action', '#');

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
            }