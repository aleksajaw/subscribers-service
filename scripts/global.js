function ajaxRequest(url, data, successCallback, errorCallback, reload = false) {
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', './config/' + url);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                handleAjaxSuccess(xhr.responseText, successCallback, reload);
            } else {
                handleAjaxError(xhr.statusText, errorCallback);
            }
        }
    };
    
    xhr.send(JSON.stringify(data));
}



function handleAjaxSuccess(response, successCallback, reload = false) {

    // LOGS
    console.log('The info we have:');
    console.log('response: ' + (response || 'no response'));
    if (typeof successCallback === 'function') {
        successCallback(response);
    }
    if (reload) {
        location.reload();
    }
}



function handleAjaxError(error, errorCallback) {

    // LOGS
    console.log('The info we have:');
    console.log('error:' + (error || 'lack of information about unknown error'));
    if (typeof errorCallback === 'function') {
        errorCallback(error);
    }
}



function consoleVariableChangeLog(newValue, keyPath) {

    console.log(`Value "${keyPath}" has been changed into: ${newValue}`);
}



function setObjWatchers(obj, keyPath = '', handleChange) {

    for (let key in obj) {
        let currentPath = keyPath ? keyPath + '.' + key : key;
        let value = obj[key];
    
        if (typeof value === 'object' && value !== null) {

            setObjWatchers(value, currentPath, handleChange);
        } else {
            setSingleWatcher(obj, key, value, handleChange, currentPath);
        }
    }
}



function setSingleWatcher(container, variable, value, handleChange, variablePath = variable){

    Object.defineProperty(container, variable, {
        get: () => {
            return value;
        },
        set: (newValue) => {
            value = newValue;
            consoleVariableChangeLog(newValue, variablePath);
            if (typeof handleChange === 'function') handleChange(value);
        }
    });
}



function getValueFromObjectPath(obj, path) {

    let keys = path.split('.');
    let value = obj;
    keys.forEach((key) => {
        value = value[key];
    });
    return value;
}



function findKeyPath(obj, targetKey, keyPath = '', showLog = false) {
    
    for (let key in obj) {
        let currentPath = keyPath ? keyPath + '.' + key : key;
        let value = obj[key];
  
        if (key === targetKey) {
            if ( showLog ) console.log(currentPath);
            return currentPath;
        }
  
        if (typeof value === 'object' && value !== null) {
            let nestedPath = findKeyPath(value, targetKey, currentPath);
            if (nestedPath) {
                return nestedPath;
            }
        }
    }
    return null;
}



function searchKeyValueDeepInside(objToSearch, targetKey, showLog = false) {

    let keyPath = findKeyPath(objToSearch, targetKey, '', showLog);
    let value = getValueFromObjectPath(objToSearch, keyPath);
    if ( showLog ) console.log(keyPath + ': ' + value);
    return value;
}



function getDataFromClassName(el, data='') {
    // make sure that we don't receive an additional class
    // after the section we want
    return el.className.split(data)[1].split(' ')[0];
}


function getDataFromClassNameByRegex(el, regexStr='', elNum=1) {
    
    if ( regexStr ) {
        return el.className.match(regexStr)[elNum];
    } else {
        return null;
    }
}