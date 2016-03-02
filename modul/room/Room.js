/**
 * The Room class represents a room on the web page and handles the communication with the server.
 */

function Room(roomID, user, mod_id) {

    this.fuserID = roomID;
    this.fuser = user;
    this.fmod_id = mod_id;

    /**
     * The function addRoom adds a new room with the given attributes.
     *
     * @param token
     * @param objRoomName
     */
    this.addRoom = function (token, objRoomName) {
        var date = Date.now();
        var fieldRoomName = document.getElementById(objRoomName);

        var roomName = fieldRoomName.value;

        if(roomName == ""){
            $(fieldRoomName).css('border-color', 'red');
            return false;
        }

        asyncKommunikation("com.php?action=1&mod_id=" + this.fmod_id + "&token=" + token + "&roomName=" + roomName + "&roomID=" + roomID + "&date=" + date);

        timmedreload();
    };

    /**
     * The delRoom function sends the command to delete the actual room.
     *
     * @param token
     */
    this.delRoom = function (token) {
        var date = Date.now();
        asyncKommunikation("com.php?action=2&mod_id=" + this.fmod_id + "&token=" + token + "&roomID=" + roomID + "&user=" + this.fuser + "&date=" + date);

        timmedreload();
    };

    /**
     * The function setOn sends the command to switch on the actuator of the room.
     *
     * @param token
     * @param idOn
     * @param idOff
     */
    this.setOn = function (token, idOn, idOff) {
        var date = Date.now();
        asyncKommunikation("com.php?action=3&mod_id=" + this.fmod_id + "&state=1&token=" + token + "&roomID=" + roomID + "&user=" + this.fuser + "&date=" + date);
        //document.getElementById(idOn).className = 'btn btn-success btn-sm';
        //document.getElementById(idOff).className = 'btn btn-danger btn-sm active';

        timmedreload();
    };

    /**
     * The function setOff sends the command to switch off the actuator of the room.
     *
     * @param token
     * @param idOn
     * @param idOff
     */
    this.setOff = function (token, idOn, idOff) {
        var date = Date.now();
        asyncKommunikation("com.php?action=3&mod_id=" + this.fmod_id + "&state=0&token=" + token + "&roomID=" + roomID + "&user=" + this.fuser + "&date=" + date);
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
        asyncKommunikation("com.php?action=4&mod_id=" + this.fmod_id + "&state=0&token=" + token + "&roomID=" + roomID + "&name=" + val + "&date=" + date);
        timmedreload();
        //alert(val);
    };
}