function EditTextField(textFieldID, commitFunction, token) {
    this.fTextFieldID = textFieldID;
    this.fToken = token;
    this.fValue = "";

    this.enableField = function () {
        var textBox = document.getElementById(this.fTextFieldID);
        textBox.removeAttribute("disabled");
        textBox.focus();

    };

    this.disableField = function () {
        var textBox = document.getElementById(this.fTextFieldID);
        this.fValue = textBox.value;
        textBox.setAttribute("disabled", "disabled");
    };

    this.disableFieldOnKey = function (key){
        var obj = document.getElementById(this.fTextFieldID);
        var test = isKeyEvent(obj , key);
        //alert(test);

    }


}