
function Panel(head, body, buttonMinMax) {
    this.fHead = head;
    this.fBody = body;
    this.fButtonMinMax = buttonMinMax;
    this.isOpen = true;

    this.minMax = function () {
        if (this.isOpen == true) {
            this.minimize();
            this.isOpen = false;
        } else {
            this.maximaize();
            this.isOpen = true;
        }
    };

    this.minimize = function () {
        //alert(this.fBody);
        var body = document.getElementById(this.fBody);
        var button = document.getElementById(this.fButtonMinMax);
        $(body).slideUp('fast');
        //body.className = 'panel-body hidden';
        button.innerHTML = "<i  class='fa'><span class='arrow_down'/></i>";
    };

    this.maximaize = function () {
        var body = document.getElementById(this.fBody);
        var button = document.getElementById(this.fButtonMinMax);
        //body.className = 'panel-body';
        $(body).slideDown('fast');
        button.innerHTML = "<i  class='fa'><span class='arrow_up'/></i>"
    };
///**
// * Created by z003drvd on 21.10.2015.
// */
//
//
//function Panel(headID,bodyID){
//
//    this.minimize = function () {
//        var body = document.getElementsByName(bodyID);
//        body.className =  "panel-body hidden";
//    };
//    this.maximize = function () {
//        var body = document.getElementsByName(bodyID);
//        body.className =  "panel-body";
//    };

}