let userList = [];
let futureUserInfo = {
        fname : '',
        email :  ''
    };
let editMode = {
        status: false,
        target: null,
        changesMade: false
    }


setObjWatchers(userList, 'userList');
setObjWatchers(futureUserInfo, 'futureUserInfo');
setObjWatchers(editMode, 'editMode');


document.addEventListener('DOMContentLoaded', function () {

    updateState();
});



function updateState() {

    updateUserList();
    updateFutureUserInfo();
}



function updateUserList() {

    let listRow = document.querySelectorAll('.user-list__row--existing-user');

    listRow.forEach( (row, i) => {

        let rowInputs = row.querySelectorAll('.user-list__input');
        let rowNr = getDataFromClassName(row, '-number-') || null;
        userList[i] = { number: rowNr, fields: {} };

        rowInputs.forEach( (input) => {

            if ( checkInputEditAbility(input) ) {
                let dataKey = getProperName(input);
                let dataVal = input.value;
                userList[i].fields[dataKey] = dataVal;
            }
        });
    });
    consoleUserListLog();
}



function trimWholeInputValue(input) {

    let newInputVal = (input.value).trim()
    if ( !input.value ) {
        let grandparentEl = input.parentNode.parentNode;
        let grandparentNr = getNrFromClass(grandparentEl);
    
        if ( grandparentNr ) {
            let inputName = getProperName(input);
            let userInList = userList.find( (user) => user.number === grandparentNr);
            let lastSavedVal = userInList.fields[inputName];
            newInputVal = lastSavedVal;
        }
    }
    input.value = newInputVal;
    return input.value;
}



function getProperName(el, key='name') {
    
    return (el[key]).replace(/[^\w]+/g, '');
}



function isValueChanged(rowNr, input, fullInfo = false) {

    let name = getProperName(input);
    let value = input.value;
    let savedValue = userList[rowNr].fields[name];
    let changeBool = savedValue != value;
    let changeInfo = null;
    let realRowNr = rowNr + 1;

    if (fullInfo) {

        changeInfo = 'In user list row ' + realRowNr + '. ' + name + ' value "' + savedValue + '"';
        changeInfo += ' is' + (changeBool ? ' not' : '') + ' the same as ';
        changeInfo += 'value "' + value + '".';
        console.log(changeInfo);

    } else {
        return changeBool;
    }
}



function checkInputEditAbility(input) {

    return !input.classList.contains('user-list__input--not-editable');
}



function isUserEditModeTarget(userNumber = null) {

    let editModeStatus = editMode.status;
    let editModeTarget = editMode.target;

    return (( editModeStatus && editModeTarget === userNumber)
        || !editModeStatus && !editModeTarget)
}



function toggleEditMode(userNumber = null, msgElements = {}) {

    editMode.status = !editMode.status;
    editMode.target = (!editMode.target && editMode.status) ? userNumber : null;

    if (!msgElements.i && editMode.status) {
        let message = !editMode.changesMade ? 'Nothing' : 'Have';
        message += ' to save in row ' + msgElements.rowNr+1 + '. with Number ' + userNumber + '.';
        console.log(message);
    }
}



function editUser(userNumber) {

    let rowNrClass = '.user-list__row--number-' + userNumber;
    let rowByNrClass = document.querySelector(rowNrClass);
    let rowNr = $('.user-list__row').index($(rowNrClass));
    let rowInputs = rowByNrClass.querySelectorAll('.user-list__input:not(.user-list__input--not-editable)');
    let i = rowInputs.length;
    let rowData = {};

    editMode.changesMade = false;

    console.log('\n\nTry to edit a user with the number: ' + userNumber);

    // verify that a user has selected a correct row
    // edit one user at a time
    if (isUserEditModeTarget(userNumber)) {

        rowData.number = userNumber;
        
        // walking in a row
        rowInputs.forEach( (input) => {

            if (editMode.status) {
                // convert e.g. 'name[]' into 'name'
                // to get a new value
                if (!input) input.value = trimWholeInputValue(input);
                let keyName = getProperName(input);
                rowData[keyName] = input.value;
                if(input.value) i--;
            }

            if (checkInputEditAbility(input)) {

                // if something was edited
                if (editMode.status) {

                    // LOG: compare the old and the new value of the input
                    isValueChanged(rowNr, input, true);

                    // if noticed the first change in the row
                    if (!editMode.changesMade) {
                        editMode.changesMade = isValueChanged(rowNr, input, false);
                    }

                    // if there are already any changes
                    // & no input left so we have all we want
                    if (editMode.changesMade && !i) {

                        editUserRequest(rowNr, rowData);
                    }
                }
                input.disabled = !input.disabled;
            }
        });
    
        toggleEditMode(userNumber, {i, rowNr});
    }
}



function editUserRequest(rowNr, rowData) {

    ajaxRequest('subscriber_edit.php', rowData,
        function (response) {
            consoleRowLog(rowData);
        },
        function (error) {
            consoleRowLog(rowData);
        },
        true
    );
}



function deleteUser(number) {

    console.log('\n\nTry to delete user with the number: ' + number);
    deleteUserRequest(number);

}



function deleteUserRequest (number) {

    ajaxRequest('subscriber_del.php', { number: number },
        function (response) {},
        function (error) {},
        true
    )
}



function addUser() {

    console.log('\n\nTry to add a new user.');

    let rowWithInputs = document.querySelector('.user-list__row--add-user');
    let rowInputs = rowWithInputs.querySelectorAll('.user-list__input');
    let rowData = {};
    let i = rowInputs.length;


    rowInputs.forEach( (input) => {

        let value = input.value;
        // convert e.g. 'name[]' into 'name'
        let keyName = getProperName(input);
        rowData[keyName] = value;
        if (value) i--;
    })

    let hasFullRowData = !i;

    if (hasFullRowData) {

        if ( confirm('Save the edit?') ) {

            addUserRequest(rowData);

        } else {
            //resetRowToSavedState(rowNr);
        }
    } else {
        // LOGS
        console.log('The info we have: ');
        consoleRowLog();
        alert("We need more info about a new user!");
    }
}



function addUserRequest(rowData) {

    ajaxRequest('subscriber_add.php', rowData,
        function () {
            consoleRowLog();
        },
        function () {},
        true 
    )
}



function updateFutureUserInfo(input) {

    if ( input ) {
        
        let newValue = trimWholeInputValue(input);
        let fieldName = getProperName(input);

        futureUserInfo[fieldName] = newValue;
    
        consoleFutureUserLog();
        input.value = newValue;
    } else {

        let futureUserRow = document.querySelector('.user-list__row--add-user');
        let futureUserFields = futureUserRow.querySelectorAll('.user-list__input');
        futureUserFields.forEach( (field) => {
            // convert e.g. 'name[]' into 'name'
            // to get a new value
            let fieldName = getProperName(field);
            futureUserInfo[fieldName] = field.value;
        })
        consoleFutureUserLog();
    }
}



function updateUserListStateRow(rowNr, rowData) {

    userList[rowNr].fields = rowData;
}



function consoleUserListLog() {
    
    console.log('userList:');
    console.log(userList);
}



function consoleRowLog(rowArg = null, info='rowData:') {

    let rowData = {};

    if ( rowArg === null) {
        rowData = futureUserInfo;
    }
    else if ( typeof rowArg === Number) {
        rowData = userList[rowNr].fields
    } else  {
        rowData = rowArg;
    }

    console.log(info);
    console.log(rowData);
}



function consoleFutureUserLog(info='FutureUserInfo:') {
    console.log('futureUserInfo: ');
    console.log(futureUserInfo);
}



function resetRowToSavedState(rowNr) {

    let rowWithInputs = document.querySelectorAll('.user-list__row')[rowNr];
    let rowInputs = rowWithInputs.querySelectorAll('.user-list__input');
}



function useRegexForEmail(text) {

    const regex = "^(?!`[^ !\"#%&'()*+,\-/:;<=>?@[\\\]^{|}~\r\n\t]`)+([\w]+([._-]?[\w]+)*@(([\w]+)\.[\w]+)+)$";
    return !regex.match(text) ? text.substr(0, text.length - 1) : text;
}

/*^(?!`[^ !"#%&'()*+,\-/:;<=>?@[\\\]^{|}~\r\n\t]`)+([\w]+([._-]?[\w]+)*@(([\w]+)\.[\w]+)+)$ */