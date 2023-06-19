let filterOptions = {
    subscribers: {
        number: true,
        fname: true,
        email: true
    },
    audit_subscribers: {
        subscriber_name: true,
        action_performed: true,
        date_added: true 
    },
    user_action: true,
    insert: true,
    update: true,
    delete: true,
    addNewUser: true
}



setObjWatchers(filterOptions, 'filterOptions', function () {});

setSingleWatcher(filterOptions, 'insert', filterOptions.insert, (value) => {

    blockFilterCheckboxes('audit-subscribers', (!value && !filterOptions.update));
})


setSingleWatcher(filterOptions, 'update', filterOptions.update, (value) => {

    blockFilterCheckboxes('audit-subscribers', (!value && !filterOptions.insert));
})


function blockFilterCheckboxes(groupName, boolValue) {

    document.querySelectorAll('.filter-nav__checkbox--option-group-' + groupName).forEach(
        (checkbox) => checkbox.disabled = boolValue
    );
}


function updateCheckboxes () {
 
    let checkboxes = document.querySelectorAll('.filter-nav__checkbox-input');
    checkboxes.forEach( checkbox => {
        let optGroupName = getDataFromClassName(checkbox, 'option-group-');
        checkbox.checked = filterOptions[optGroupName][checkbox.name] || filterOptions[optGroupName];
    })
}



function changeUserListFilterOptions(optGroupName, checkbox, filterTarget = '') {

    if ( optGroupName != '' ) filterOptions[optGroupName][checkbox.name] = checkbox.checked;
    else filterOptions[checkbox.name] = checkbox.checked;


    if ( filterTarget === 'row' ) changeDisplaying(checkbox, 'row');
    else changeDisplaying(checkbox, 'cell');
}



function changeDisplaying(checkbox, elName='') {

    if ( elName ) {

        let isRow = elName === 'row';
        let elClass = '.user-list__' + elName + '--' + checkbox.name.replace('_', '-');
        let elements = document.querySelectorAll(elClass);

        let displayNoneClass = 'display-none';

        // arrays for catching rows for the specific user number
        let elNumber = null;
        let elNumbers = [];

        let searchClass = '';

        elements.forEach( (el) =>{
            
            if ( isRow ) { // get user's number (id)
                elNumber = getDataFromClassNameByRegex( el, /user-list__row--number-(\d+)/, 1);

                // create array with unique numbers
                if ( !elNumbers.includes(elNumber) ) elNumbers.push(elNumber);
            }

            if ( !checkbox.checked ) {
                el.classList.add(displayNoneClass);

            } else {
                el.classList.remove(displayNoneClass);                
            }
            
        })

        if ( isRow ) manageUserMainInfoRowDisplaying(elNumbers);
        else searchClass = "> .user-list__cell:not(.user-list__cell--user-action)";

        displayingReserveRowForEmptyTable(searchClass);
    }
}


function displayingReserveRowForEmptyTable(searchClass=''){

    let isEmptyTable = !document.querySelectorAll('.user-list__row--user-data' + searchClass + ':not(.display-none)').length;
    let noUserDataRow = document.querySelector('.user-list__row--no-data');

    if ( isEmptyTable ) {
        noUserDataRow.classList.remove('display-none');
        //document.querySelector('.filter-nav__checkbox--option-group-user_action').checked = false;
    
    } else noUserDataRow.classList.add('display-none');
}


function manageUserMainInfoRowDisplaying(numbers = []) {

    // use variables for the same classes in different places
    let rowNumClassBase = '.user-list__row--number-';
    let mainRowClassBase = 'user-list__row--main-substitute';
    let displayNoneClass = 'display-none';

    numbers.forEach( (num) => {

        let mainUserInfoRow = document.querySelector(rowNumClassBase + num + '.' + mainRowClassBase);
        
        // if some rows are not displayed & the user's main info hasn't showed up yet 
        if ( document.querySelector(rowNumClassBase + num + '.' + displayNoneClass)
            && !mainUserInfoRow ) {
                
            // get the first row that displays
            let newUserMainRow = document.querySelector(rowNumClassBase + num + ':not(.' + displayNoneClass + ')');
            if ( newUserMainRow ) newUserMainRow.classList.add(mainRowClassBase);
            
        // or if everything is displayed, remove useless class
        } else {
            if ( mainUserInfoRow ) mainUserInfoRow.classList.remove(mainRowClassBase);
        }
    })
}



document.addEventListener('DOMContentLoaded', () => {

    updateStyles();
    updateCheckboxes();
});



function updateStyles() {

    let filterNavHeight = document.querySelector('.filter-nav').offsetHeight;

    document.querySelector('.user-list__view').style.marginTop = filterNavHeight + 20 + 'px';
}