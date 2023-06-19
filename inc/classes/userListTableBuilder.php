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


        public function userDataRow($number=null, $cells='', $class = '') {
            
            $rowClass = 'user-list__row';


            if ( $number != null ) $rowClass .= ' user-list__row--existing-user user-list__row--number-' . $number;

            if ( $class ) $rowClass .= ' ' . $class;
            
            $row = '<tr class="' . $rowClass . '"' . '>';

                foreach ($cells as $cell) {
                    $row .= $cell;
                }

            $row .= '</tr>';

            $this->rows[] = $row;
        }


        public function noUserDataRow($class = '') {
            
            if ( $class ) $class = ' ' . $class;

            $this->rows[] = "<tr class='user-list__row user-list__row--no-data" . $class . "'>
                        <td class='user-list__cell user-list__cell--no-data' colspan='10'>
                            Brak danych w tabeli
                        </td>
                    </tr>";
        }


        public function userDataCells($data = ['number' => null, 'fname' => null, 'email' => null, 'action_performed' => null,'date_added' => null]) {

            $cellName = $this->userInput('fname', 'fname[]', $data['fname']);
            $cellEmail = isset($data['email']) ? $this->userInput('email', 'email[]', $data['email']) : '#';
            $cellAction_performed = ($data['action_performed']) ? $this->userStaticInfoCell('action-performed', $data['action_performed']) : null;
            $cellDate_added = ($data['date_added']) ? $this->userStaticInfoCell('date-added', $data['date_added']) : null;

            $cells = [
                $this->userNumberCell($data['number']),
                $this->userInputCell('fname', $cellName),
                $this->userInputCell('email', $cellEmail),
                $cellAction_performed,
                $cellDate_added,
            ];
            return $cells;
        }
        

        public function newUserDataCells($number = null) {
            
            
            $cellName = $this->newUserInput('fname', 'fname[]', '', 'updateFutureUserInfo(this)', false);
            $cellEmail = $this->newUserInput('email', 'email[]', '', 'updateFutureUserInfo(this)', false);

            $cells = [
                        $this->userNumberCell(null),
                        $this->userInputCell('fname', $cellName),
                        $this->userInputCell('email', $cellEmail),
                        $this->userEmptyCell('', 'colspan=10'),
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


        public function userStaticInfoCell($className='', $info='') {

            return $this->userCell('user-list__cell--static-info', $className, $info);
        }


        public function userInputCell($cellName = '', $input ) {
            
            return $this->userCell('', $cellName, $input);
        }


        public function userEmptyCell($cellName='', $cellProperty='') {
            
            return $this->userCell('user-list__cell--empty', $cellName, '#', $cellProperty);
        }


        public function userCell($class = '', $cellName = '', $content = '', $cellProperty = '') {

            $class = $class ? ' ' . $class : '';
            $class .= $cellName ? " user-list__cell--" . $cellName : '';

            return "<td class='user-list__cell" . $class . "' ". $cellProperty .">"
                        . $content .
                    "</td>";
        }


        public function userInput($class = '', $name = '', $value = '', $type='text', $onChange = 'trimWholeInputValue(this)', $disabled = true ) {
            
            $disabled = $disabled ? ' disabled' : '';
            $type = $type ? $type : 'text';

            return "<input
                        class='user-list__input user-list__input--". $class ."' name='" . $name . "' value='" . $value . "' placeholder='" . $value . "'
                        type='" . $type . "' onChange='" . $onChange . "' required" . $disabled .
                    ">";
        }


        public function newUserInput($class = '', $name = '', $value = '', $type='text', $onChange = 'trimWholeInputValue(this)') {
            
            $type = $type ? $type : 'text';
            $placeholders = [
                                'fname[]' => 'Imię i nazwisko',
                                'email[]' => 'adres@email.pl'
            ];
            $placeholder = '';
            $placeholder = $placeholders[$name] ?: '';

            return "<input
                        class='user-list__input user-list__input--". $class ."' name='" . $name . "' value='" . $value . "'" . "placeholder='" . $placeholder ."'
                        type='" . $type . "' onChange='" . $onChange . "' required" .
                    ">";
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