    <nav class="filter-nav">
        
        <h3>Filter Options</h3>
        <div class="filter-nav__checkboxes-list">

            <?php 
            
                require( './inc/classes/userListFilterBuilder.php');

                $tableName = 'audit_subscribers';
                $query = "SELECT action_performed, date_added FROM audit_subscribers";

                $stmt = $mysqli->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
    
                            
                $userListFilterBuilder = new userListFilterBuilder();

                if ($result) {
                    $fieldInfo = mysqli_fetch_fields($result);
                
                    $options1 = [];
                    foreach ($fieldInfo as $field) {
                        $columnName = $field->name;
                        $options1[] = $userListFilterBuilder->userListFilterCheckbox(false, $tableName, $columnName);
                    }
                    $userListFilterBuilder->addOptionGroup($options1);
                }
                
                $tableName = 'subscribers';
                $query = "SELECT number, fname, email FROM subscribers";
                $stmt = $mysqli->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result) {
                    $fieldInfo = mysqli_fetch_fields($result);
                
                    $options2 = [];
                    foreach ($fieldInfo as $field) {
                        $columnName = $field->name;
                        $options2[] = $userListFilterBuilder->userListFilterCheckbox(false, $tableName, $columnName);
                    }
                    $userListFilterBuilder->addOptionGroup($options2);
                }
                $options3[] = $userListFilterBuilder->userListFilterCheckbox(false, 'user_action', 'user_action');
                $userListFilterBuilder->addOptionGroup($options3);

                $userListFilterBuilder->render();
            ;?>
        </div>
        <style>
            /* Floating navigation bar styles */
            .filter-nav {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                background-color: #f2f2f2;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
                text-align:center
            }
            .filter-nav__checkboxes-list {
            }
            .filter-nav__checkbox-label {
                display: inline-block;
                padding: 15px 30px;
                margin:0;
            }
            .filter-nav h3 {
                margin: 15px 20px 0;
            }
            .filter-nav button {
                margin-top: 10px;
            }
        </style>
    </nav>