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
        addNewUser: true
    }



function updateCheckboxes () {
     
    let checkboxes = document.querySelectorAll('.filter-nav__checkbox-input');
    checkboxes.forEach( checkbox => {
        let table = getDataFromClassName(checkbox, 'option-group-');
        checkbox.checked = filterOptions[table][checkbox.name] || filterOptions[table];
    })
}



function changeUserListFilterOptions(table, checkbox) {

    if ( table != checkbox.name ) filterOptions[table][checkbox.name] = checkbox.checked;
    else filterOptions[checkbox.name] = checkbox.checked;
    let cellClass = '.user-list__cell--' + checkbox.name.replace('_', '-');
    document.querySelectorAll(cellClass).forEach( cell =>{
        if ( !checkbox.checked ) cell.classList.add('display-none');
        else cell.classList.remove('display-none');
    })
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