

        <?php

            require('./config/db.php');
                        
            require('./inc/view-subscribers_filter-nav.php');
            
            // here we have the version for the unchanged subscriber's name
            $query = "SELECT subscribers.number, subscribers.fname, subscribers.email, audit_subscribers.action_performed, audit_subscribers.date_added 
                        FROM audit_subscribers JOIN subscribers ON audit_subscribers.subscriber_name = subscribers.fname  ORDER BY number DESC";

            $stmt = $mysqli->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

        ?>

        <div class="user-list__view">
            <h2>
                Wyświetl użytkowników
            </h2>
                
                <?php
                        
                        require( './inc/classes/userListTableBuilder.php');


                        $userListTableBuilder = new userListTableBuilder();


                        //echo $userListTableBuilder->userListHeader(['#','Name','Email','Action Performed', 'Date', 'Action']);

                        
                        $headingsInfo = mysqli_fetch_fields($result);
                        $headingsArr[] = 'user_action';

                        foreach($headingsInfo as $headingInfo) {
                            $headingName = $headingInfo->name;
                            $headingsArr[] = $headingName;
                        }
                        $userListTableBuilder->userListHeader($headingsArr);
                        
                        $prevUserNumber = null;
                        $nextRowsForSameUser = ['action_performed', 'date_added'];

                        

                        $actionClass = [
                                'insert' => 'Insert a new subscriber',
                                'update' => 'Updated a subscriber',
                                'delete' => 'Deleted a subscriber'
                            ];


                        $rowClass = '';
                        $noDataRowClass = '';

                        
                        if ($result->num_rows > 0) {

                            while ($row = $result->fetch_assoc()) {
                                                  
                                $userNumber = isset($row['number']) ? $row['number'] : null;
                                $hasUserNumber = $userNumber && $userNumber === $prevUserNumber;

                                $isRowHidden = $hasUserNumber;
                                $rowClass = ( $isRowHidden ) ? 'user-list__row--hidden-user ' : '';

                                $userActionCell = $userListTableBuilder->userActionCell(['edit'=>$row["number"],'del'=>$row["number"]]);

                                $userColumns = [];

                                foreach($headingsInfo as $column) {
                                    $colName = $column->name;
                                    $userColumns[$colName] = $row[$colName];
                                }

                                $userRowCells = array_merge(
                                                    [$userActionCell], 
                                                    $userListTableBuilder->userDataCells($userColumns)
                                                );


                                if ( $row['action_performed'] != '') {
                                    $actionClassKey = array_search($row['action_performed'], $actionClass);
                                    $rowClass .= 'user-list__row--' . $actionClassKey;
                                }

                                $userListTableBuilder->userDataRow($userNumber, $userRowCells, $rowClass);

                                $prevUserNumber = $userNumber;
                            }
                            $noDataRowClass = 'display-none';
                        }
                    
                        $userListTableBuilder->noUserDataRow($noDataRowClass);
                        
                        
                        $userActionCell = $userListTableBuilder->userActionCell(['add' => true]);
                        $newUserCells = array_merge(
                                            [$userActionCell],
                                            $userListTableBuilder->newUserDataCells(null)
                                        );

                        $userListTableBuilder->userDataRow(null, $newUserCells, 'user-list__row--add-user') .


                        $userListTableBuilder->render();

                        $mysqli->close();
                    ?>

                    
        </div>
        <footer>
            
            <script src="./scripts/userListTable.js"></script>
            <script src="./scripts/usersListFilter.js"></script>
        </footer>
