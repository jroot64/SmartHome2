/**
 * The Group class represents a group on the web page and handles the communication with the server.
 */

function Group(groupID, user, mod_id) {

    this.fuserID = groupID;
    this.fuser = user;
    this.fmod_id = mod_id;

    /**
     * The function addGroup adds a new group with the given attributes.
     * 
     * @param token
     * @param objGroupName
     */
    this.addGroup = function (token, objGroupName) {
        var date = Date.now();
        var fieldGroupName = document.getElementById(objGroupName);

        var groupName = fieldGroupName.value;

        if(groupName == ""){
            $(fieldGroupName).css('border-color', 'red');
            return false;
        }

        asyncKommunikation("com.php?action=1&mod_id=" + this.fmod_id + "&token=" + token + "&groupName=" + groupName + "&groupID=" + groupID + "&date=" + date);

        timmedreload();
    };

    /**
     * The delGroup function sends the command to delete the actual group.
     * 
     * @param token
     */
    this.delGroup = function (token) {
        var date = Date.now();
        asyncKommunikation("com.php?action=2&mod_id=" + this.fmod_id + "&token=" + token + "&groupID=" + groupID + "&user=" + this.fuser + "&date=" + date);

        timmedreload();
    };

    /**
     * The function setOn sends the command to switch on the actuator of the group.
     *
     * @param token
     * @param idOn
     * @param idOff
     */
    this.setOn = function (token, idOn, idOff) {
        var date = Date.now();
        asyncKommunikation("com.php?action=3&mod_id=" + this.fmod_id + "&state=1&token=" + token + "&groupID=" + groupID + "&user=" + this.fuser + "&date=" + date);
        //document.getElementById(idOn).className = 'btn btn-success btn-sm';
        //document.getElementById(idOff).className = 'btn btn-danger btn-sm active';

        timmedreload();
    };

    /**
     * The function setOff sends the command to switch off the actuator of the group.
     *
     * @param token
     * @param idOn
     * @param idOff
     */
    this.setOff = function (token, idOn, idOff) {
        var date = Date.now();
        asyncKommunikation("com.php?action=3&mod_id=" + this.fmod_id + "&state=0&token=" + token + "&groupID=" + groupID + "&user=" + this.fuser + "&date=" + date);
        //document.getElementById(idOn).className = 'btn btn-success btn-sm active';
        //document.getElementById(idOff).className = 'btn btn-danger btn-sm';

        timmedreload();
    };

    /**
     * The function changeName reads the value of the given editTextField and calls the change request.
     * After the change request the reload is performed to update the corresponding values on the page.
     *
     * @param token
     * @param jsObj
     */
    this.changeName = function (token, jsObj) {
        //alert(jsObj);
        var val = document.getElementById(jsObj).value;
        var date = Date.now();
        asyncKommunikation("com.php?action=4&mod_id=" + this.fmod_id + "&state=0&token=" + token + "&groupID=" + groupID + "&name=" + val + "&date=" + date);
        timmedreload();
        //alert(val);
    };
}