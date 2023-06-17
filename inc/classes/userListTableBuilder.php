<?php 




    class userListTableBuilder {


        private $headingsRow, $rows;


        public function __construct() {
            $this->rows = [];
            $this->headingsRow = '';
        }


        public function userListHeader($headings=[]) {
            
            foreach($headings as $heading){
                $headingClass = str_replace('_', '-', $heading);
                $headingsCell[] = '<th class="user-list__cell user-list__cell--' . $headingClass . '">' . $heading . '</th>'; 
            };
            
            $hRow = '<tr>';
            foreach ($headingsCell as $cell) {
                $hRow .= $cell;
            }
            $hRow .= '</tr>';
            $this->headingsRow = $hRow;
        }


        public function userInfoRow($number=null, $cells, $class = '') {
            
            $rowClass = 'user-list__row';

            if ( $class ) $rowClass .= ' ' . $class;

            if ( $number != null ) $rowClass .= ' user-list__row--existing-user user-list__row--number-' . $number;

            $row = '<tr class="' . $rowClass . '"' . '>';

                foreach ($cells as $cell) {
                    $row .= $cell;
                }

            $row .= '</tr>';

            $this->rows[] = $row;
        }


        public function noUserDataRow() {
            
            return "<tr>
                        <td class='user-list__cell user-list__cell--no-data' colspan='4'>
                            Brak danych w tabeli
                        </td>
                    </tr>";
        }


        public function userInfoCells($number = null, $fname = null, $email = null, $action_performed = null, $date_added = null) {

            $cells = [
                $this->userNumberCell($number),
                $this->userInputCell('fname', 'fname[]', $fname, '', ''),
                $this->userInputCell('email', 'email[]', $email, '', 'email'),
                $this->userInfoCell('action-performed', $action_performed),
                $this->userInfoCell('date-added', $date_added),
            ];
            return $cells;
        }
        

        public function newUserInfoCells($number = null) {
            
            $cells = [
                        $this->userNumberCell(null),
                        $this->userInputCell('fname', 'fname[]', '', 'Imię i nazwisko', '', 'updateFutureUserInfo(this)', false),
                        $this->userInputCell('email', 'email[]', '', 'email', 'email', 'updateFutureUserInfo(this)', false),
                        $this->userEmptyCell('action-performed'),
                        $this->userEmptyCell('date-added'),
                    ];

            return $cells;
        }


        public function userActionCell($actionArgs=[]) {
            
            return "<td class='user-list__cell user-list__cell--user-action'>".
                        
                        (array_key_exists('edit', $actionArgs) ? $this->editUserActionBtn($actionArgs['edit']) :'') .

                        (array_key_exists('del', $actionArgs) ? $this->delUserActionBtn($actionArgs['del']) :'') .

                        ((array_key_exists('add', $actionArgs) && $actionArgs['add'] === true)
                            ? $this->addActionBtn()
                            : '') .

                    "</td>";
        }


        public function userNumberCell($number = null) {

            $cell = "<td class='user-list__cell user-list__cell--number'>";
                            
            $cell .= ( $number === null) ? '#'
                        : "<input
                            class='user-list__input user-list__input--number user-list__input--not-editable'
                            type='number' name='number[]' value='" . $number. "' hidden disabled
                        >" . $number;

            $cell .= "</td>";

            return $cell;
        }


        public function userInfoCell($class, $info) {
            

            return "<td class='user-list__cell user-list__cell--info user-list__cell--" . $class . "'>".
                    
                        $info

                    . "</td>";
        }

        
        public function userInputCell($class = '', $name = '', $value = '', $placeholder = '', $type='text', $onChange = 'trimWholeInputValue(this)', $disabled = true ) {
            
            $disabled = $disabled ? ' disabled' : '';
            $type = $type ? $type : 'text';

            return "<td class='user-list__cell user-list__cell--" . $class . "'>             
                        <input
                            class='user-list__input user-list__input--". $class ."' name='" . $name . "' value='" . $value . "'" . "placeholder='" . $placeholder ."'
                            type='" . $type . "' onChange='" . $onChange . "' required" . $disabled .
                        ">
                    
                    </td>";
        }


        public function userEmptyCell($class) {
            
            return "<td class='user-list__cell user-list__cell--empty user-list__cell--" . $class . "'>#</td>";
        }


        public function delUserActionBtn($funcArg='') {

            return $this->actionBtn('del', 'deleteUser(' . $funcArg . ')', 'Delete');
        }


        public function editUserActionBtn($funcArg='') {

            return $this->actionBtn('edit', 'editUser(' . $funcArg . ')', 'Edit');
        }


        public function addActionBtn() {

            return $this->actionBtn('add', 'addUser()', 'Add');
        }


        public function actionBtn($class='', $onClick='', $text='') {

            return "<button
                        class='user-list__action-button user-list__action-button--" . $class . "'
                        type='button' onClick='" . $onClick . "'
                    >
                        <span class='user-list__action-button-text'>" .
                            $text
                        . "</span>
                    </button>";
        }


        public function render() {
            
            $table = '<table class="user-list__table">
                        <table>
                            <tbody> ' .
                                $this->headingsRow;
            
                                if (empty($this->rows)) {
                                    $table .= $this->noUserDataRow();
                                } else {
                                    foreach ($this->rows as $row) {
                                        $table .= $row;
                                    }
                                }
            $table .=        '</tbody>
                        </table>
                    </table>';
            echo $table;
        }
    }

    
?>