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
        addNewUser: true
    }



function updateCheckboxes () {
     
    let checkboxes = document.querySelectorAll('.filter-nav__checkbox-input');
    checkboxes.forEach( checkbox => {
        let optGroupName = getDataFromClassName(checkbox, 'option-group-');
        checkbox.checked = filterOptions[optGroupName][checkbox.name] || filterOptions[optGroupName];
    })
}



function changeUserListFilterOptions(optGroupName, checkbox, filterTarget ='') {

    if ( optGroupName != '' ) filterOptions[optGroupName][checkbox.name] = checkbox.checked;
    else filterOptions[checkbox.name] = checkbox.checked;
    
    
    if ( filterTarget === 'row' ) changeDisplaying(checkbox, 'row');
    else changeDisplaying(checkbox, 'cell');
}



function changeDisplaying(checkbox, el='') {

    if ( el ) {
        let elClass = '.user-list__' + el + '--' + checkbox.name.replace('_', '-');
        document.querySelectorAll(elClass).forEach( el =>{
            if ( !checkbox.checked ) el.classList.add('display-none');
            else el.classList.remove('display-none');
        })
    }
}



document.addEventListener('DOMContentLoaded', function () {

    updateStyles();
    updateCheckboxes();
});



function updateStyles() {
    
    let filterNavHeight = document.querySelector('.filter-nav').offsetHeight;

    document.querySelector('.user-list__view').style.marginTop = filterNavHeight + 20 + 'px';
}



setWatchers(filterOptions, 'filterOptions', function () {});