/**
 * The User class represents a user on the web page and handles the communication with the server.
 */

function User(userID, mod_id) {
    //alert(userID);
    this.fuserID = userID;
    this.fmod_id = mod_id;

    /**
     * The function addUser adds a new user with the given attributes.
     * 
     * @param token
     * @param objLogin
     * @param objName
     * @param objPassword
     */
    this.addUser = function (token, objLogin, objName, objPassword) {
        var date = Date.now();
        //var login = document.getElementById(objLogin).value;
        //var name = document.getElementById(objName).value;
        //var hash = CryptoJS.SHA256(document.getElementById(objPassword).value);

        var fieldName = document.getElementById(objName);
        var fieldLogin = document.getElementById(objLogin);
        var fieldPassword = document.getElementById(objPassword);

        var login = fieldLogin.value;
        var name = fieldName.value;
        var hash = CryptoJS.SHA256(fieldPassword.value);

        if(login == "" || name == ""|| fieldPassword.value == ""){
            if( login == ""){
                $(fieldLogin).css('border-color', 'red');
            }else{
                $(fieldLogin).css('border-color', '');
            }
            if( name == ""){
                $(fieldName).css('border-color', 'red');
            }else{
                $(fieldName).css('border-color', '');
            }
            if(fieldPassword.value == ""){
                $(fieldPassword).css('border-color', 'red');
            }else{
                $(fieldPassword).css('border-color', '');
            }
            return false;
        }


        //alert('Password Hash:' + hash + ' Username:' + name + ' login:' + login);
        asyncKommunikation("com.php?action=2&mod_id=" + this.fmod_id + "&token=" + token + "&login=" + login + "&userID=&userName=" + name + "&hash=" + hash + "&date=" + date);

        timmedreload();
    };

    /**
     * The delUser function sends the command to delete the actual user.
     * 
     * @param token
     */
    this.delUser = function (token) {
        var date = Date.now();
        asyncKommunikation("com.php?action=3&mod_id=" + this.fmod_id + "&token=" + token + "&userID=" + userID + "&date=" + date);

        timmedreload();
    };

    /**
     * The function setActive sends the command to switch the user to the given state and handles the style changes.
     * 
     * @param token
     * @param idOn
     * @param idOff
     */
    this.setActive = function (token, idOn, idOff) {
        var date = Date.now();
        asyncKommunikation("com.php?action=1&mod_id=" + this.fmod_id + "&state=1&token=" + token + "&userID=" + userID + "&date=" + date);
        document.getElementById(idOn).className = 'btn btn-success btn-sm';
        document.getElementById(idOff).className = 'btn btn-danger btn-sm active';
        //alert('active');
        //timmedreload();
    };

    /**
     * The function setInactive sends the command to switch the user to the given state and handles the style changes.
     * 
     * @param token
     * @param idOn
     * @param idOff
     */
    this.setInactive = function (token, idOn, idOff) {
        var date = Date.now();
        //alert(userID);
        asyncKommunikation("com.php?action=1&mod_id=" + this.fmod_id + "&state=0&token=" + token + "&userID=" + userID + "&date=" + date);
        document.getElementById(idOn).className = 'btn btn-success btn-sm active';
        document.getElementById(idOff).className = 'btn btn-danger btn-sm';
        //alert('inactive');
        //timmedreload();
    };
}