/**
 * The Actuator class symbolizes the actuator on the web page and
 * handles the communication with the server.
 */

function Actuator(houseCode, deviceCode, user, modul_id, rowID) {
    this.houseCode = houseCode;
    this.devideCode = deviceCode;
    this.user = user;
    this.fmod_id = modul_id;
    this.frowID = rowID;
    //alert(rowID);


    /**
     * The function setOn sends the command to switch the actuator to the given state and handles the style changes.
     *
     * @param token
     * @param idOn
     * @param idOff
     */
    this.setOn = function (token, idOn, idOff) {
        var date = Date.now();
        asyncKommunikation("com.php?action=1&mod_id=" + this.fmod_id + "&token=" + token + "&device=" + this.devideCode + "&housecode=" + this.houseCode + "&user=" + user + "&status=1&date=" + date);
        document.getElementById(idOn).className = 'btn btn-success btn-sm';
        document.getElementById(idOff).className = 'btn btn-danger btn-sm active';
    };

    /**
     * The function setOff sends the command to switch the actuator to the given state and handles the style changes.
     *
     * @param token
     * @param idOn
     * @param idOff
     */
    this.setOff = function (token, idOn, idOff) {
        var date = Date.now();
        asyncKommunikation("com.php?action=1&mod_id=" + this.fmod_id + "&token=" + token + "&device=" + this.devideCode + "&housecode=" + this.houseCode + "&user=" + user + "&status=0&date=" + date);
        document.getElementById(idOn).className = 'btn btn-success btn-sm active';
        document.getElementById(idOff).className = 'btn btn-danger btn-sm';
    };

    /**
     * The function setAktiv sends the command to switch the actuator to the given state and handles the style changes.
     *
     * @param token
     * @param idOn
     * @param idOff
     * @param DidOn
     * @param DidOff
     */
    this.setAktiv = function (token, idOn, idOff, DidOn, DidOff) {
        var date = Date.now();
        asyncKommunikation("com.php?action=2&mod_id=" + this.fmod_id + "&token=" + token + "&device=" + this.devideCode + "&housecode=" + this.houseCode + "&status=1&date=" + date);
        document.getElementById(idOff).className = 'btn btn-danger btn-sm active';
        document.getElementById(idOn).className = 'btn btn-success btn-sm';
        document.getElementById(DidOn).removeAttribute("disabled");
        document.getElementById(DidOff).removeAttribute("disabled");
    };

    /**
     * The function setInaktiv sends the command to switch the actuator to the given state and handles the style changes.
     *
     * @param token
     * @param idOn
     * @param idOff
     * @param DidOn
     * @param DidOff
     */
    this.setInaktiv = function (token, idOn, idOff, DidOn, DidOff) {
        var date = Date.now();
        asyncKommunikation("com.php?action=2&mod_id=" + this.fmod_id + "&token=" + token + "&device=" + this.devideCode + "&housecode=" + this.houseCode + "&status=0&date=" + date);
        document.getElementById(idOn).className = 'btn btn-success btn-sm active';
        document.getElementById(idOff).className = 'btn btn-danger btn-sm';
        document.getElementById(DidOn).setAttribute("disabled", "disabled");
        document.getElementById(DidOff).setAttribute("disabled", "disabled");

    };

    /**
     * The function addGroup sends the command to add the group
     * and reloads the page.
     *
     * @param token
     * @param Obj
     */
    this.addGroup = function (token, Obj) {
        var date = Date.now();
        var sObj = document.getElementById(Obj);
        var val = readSelect(sObj);
        asyncKommunikation("com.php?action=3&mod_id=" + this.fmod_id + "&token=" + token + "&device=" + this.devideCode + "&housecode=" + this.houseCode + "&group_id=" + val + "&date=" + date);
        timmedreload();
        //location.reload();
    };

    /**
     * The function delGroup sends the command to delete the actuator-group association
     * and reloads the page.
     *
     * @param token
     * @param val
     */
    this.delGroup = function (token, val) {
        var date = Date.now();
        asyncKommunikation("com.php?action=4&mod_id=" + this.fmod_id + "&token=" + token + "&device=" + this.devideCode + "&housecode=" + this.houseCode + "&group_id=" + val + "&date=" + date);
        timmedreload();
        //location.reload();
    };

    /**
     * The function delGroup sends the command to delete the actuator-group association
     * and reloads the page.
     *
     * @param token
     * @param Obj
     */
    this.changeRoom = function (token, Obj) {
        var date = Date.now();
        var sObj = document.getElementById(Obj);
        var val = readSelect(sObj);
        asyncKommunikation("com.php?action=5&mod_id=" + this.fmod_id + "&token=" + token + "&device=" + this.devideCode + "&housecode=" + this.houseCode + "&room_id=" + val + "&date=" + date);
    };

    /**
     * The function newActuator adds a new Actuator with the given attributes.
     *
     * @param token
     * @param objDeviceCode
     * @param objHouseCode
     * @param objDropdown
     */
    this.newActuator = function (token, objDeviceCode, objHouseCode, objDropdown) {
        var date = Date.now();
        var sObj = document.getElementById(objDropdown);
        var val = 1;
        if(sObj.getAttribute("disabled") != "disabled"){
            val = readSelect(sObj);
        }
        var fieldDeviceCode = document.getElementById(objDeviceCode);
        var fieldHouseCode = document.getElementById(objHouseCode);

        var fDeviceCode = fieldDeviceCode.value;
        var fHouseCode = fieldHouseCode.value;

        if(fDeviceCode == "" || fHouseCode == ""){
            if( fHouseCode == ""){
                $(fieldHouseCode).css('border-color', 'red');
            }else{
                $(fieldHouseCode).css('border-color', '');
            }
            if(fDeviceCode == ""){
                $(fieldDeviceCode).css('border-color', 'red');
            }else{
                $(fieldDeviceCode).css('border-color', '');
            }
            return false;
        }

        //alert("com.php?action=6&token=" + token + "&device=" + fDeviceCode + "&housecode=" + fHouseCode + "&room_id=" + val + "&date=" + date);
        asyncKommunikation("com.php?action=6&mod_id=" + this.fmod_id + "&token=" + token + "&device=" + fDeviceCode + "&housecode=" + fHouseCode + "&room_id=" + val + "&date=" + date);

        //this.devideCode = fDeviceCode;
        //this.houseCode = fHouseCode;

        //this.changeRoom(token, objDropdown);
        timmedreload();
    };

    /**
     * The delActuator function sends the command to delete the actual actuator.
     *
     * @param token
     */
    this.delActuator = function (token) {
        //alert(this.frowID);
        var date = Date.now();
        asyncKommunikation("com.php?action=7&mod_id=" + this.fmod_id + "&token=" + token + "&device=" + this.devideCode + "&housecode=" + this.houseCode + "&date=" + date);
        rowSlideUp(this.frowID);
        //timmedreload();
    };
}