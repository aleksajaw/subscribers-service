

<?php

require('./config/db.php');
            
require('./inc/view-subscribers_filter-nav.php');

?>


<div class="user-list__view">
<h2>
    Wyświetl użytkowników
</h2>
    
    <?php
            
            require( './inc/view-subscribers_existing.php');


            $userListTableBuilder->render();

            $mysqli->close();
        ?>

        
</div>
<footer>

<script src="./scripts/userListTable.js"></script>
<script src="./scripts/usersListFilter.js"></script>
</footer>
