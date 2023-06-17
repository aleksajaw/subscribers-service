

        <?php

            require('./config/db.php');
                        
            require('./inc/view-subscribers_filter-nav.php');
            
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


                        /*1. Widok wyświetlający nazwę użytkowników oraz datę ich dodania

                        2. Widok wyświetlający nazwę użytkowników oraz datę ich usunięcia 

                        3. Widok wyświetlający nazwę użytkowników oraz datę ich edycji 

                        4. Widok wyświetlający nazwę już usuniętych użytkowników oraz daty ich dodania i usunięcia 

                        5. Widok wyświetlający tylko istniejących użytkowników (bez korzystania z tabelki subscribers).

                        [Uwaga, dla podpunktu 5, zakładamy że użytkownicy nie zmienili nazwy]

                        Stwórz stronę internetową wyświetlającą wszystkie powyższe pięć widoków, każdy w kolejnej tabeli.



                        Na ocenę 5:

                        Swtórz stronę internetową wyświetlającą jedną tabelę zdefiniowaną w formularzu przez użytkownika.
                        Użytkownik poprzez formularz na stronie modyfikuje swój własny widok tabeli.*/

                        
                        $headingsInfo = mysqli_fetch_fields($result);
                        $headingsArr[] = 'user_action';

                        foreach($headingsInfo as $headingInfo) {
                            $headingsArr[] = $headingInfo->name;
                        }
                        $userListTableBuilder->userListHeader($headingsArr);
                        
                        $prevUserNumber = null;
                        $nextRowsForSameUser = ['action_performed', 'date_added'];

                        if ($result->num_rows > 0) {

                            while ($row = $result->fetch_assoc()) {
                                                  
                                $userNumber = $row['number'];

                                $userActionCell = ($row['number'] != $prevUserNumber)
                                                    ? $userListTableBuilder->userActionCell(['edit'=>$row["number"],'del'=>$row["number"]])
                                                    : $userListTableBuilder->userActionCell([]);

                                $userColumns = [];

                                foreach($headingsInfo as $column) {
                                    $colName = $column->name;
                                    $userColumns[] = ( $row['number'] != $prevUserNumber || in_array($colName, $nextRowsForSameUser) )
                                                        ? $row[$colName]
                                                        : '';
                                }

                                $userRowCells = array_merge(
                                                    [$userActionCell], 
                                                    $userListTableBuilder->userInfoCells(...$userColumns)
                                                );

                                $userListTableBuilder->userInfoRow($userNumber, $userRowCells);

                                $prevUserNumber = $row['number'];
                            }

                        } else {

                            $userListTableBuilder->noUserDataRow();
                        }
                    
                        $userActionCell = $userListTableBuilder->userActionCell(['add' => true]);
                        $newUserCells = array_merge(
                                            [$userActionCell],
                                            $userListTableBuilder->newUserInfoCells(null)
                                        );

                        $userListTableBuilder->userInfoRow(null, $newUserCells, 'user-list__row--add-user') .


                        $userListTableBuilder->render();

                        $mysqli->close();
                    ?>

                    
        </div>
        <footer>
            
            <script src="./scripts/userListTable.js"></script>
            <script src="./scripts/usersListFilter.js"></script>
        </footer>
