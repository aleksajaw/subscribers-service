<?php
    
    $userListTableBuilder->noUserDataRow($noDataRowClass);
    
    $userActionCell = $userListTableBuilder->userActionCell(['add' => true, 'class' => 'user-list__cell--always-visible']);
    $newUserCells = array_merge(
                        [$userActionCell],
                        $userListTableBuilder->newUserDataCells(null)
                    );

    $userListTableBuilder->userDataRow(null, $newUserCells, 'user-list__row--add-user');

;?>