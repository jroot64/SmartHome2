<?php

/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 21.10.2015
 * Time: 22:30
 */
class EditTextField
{
    private $jsID;
    private $fieldID;
    private $value;
    private $changeAble;
    private $commitFunction;
    private $token;
    private $buttonID;

    public function __construct()
    {
//        echo "rest";
        $this->changeAble = true;
        $this->value = "";
        $this->fieldID = DOM_id::getID();
        $this->jsID = DOM_id::getID();
        $this->buttonID = DOM_id::getID();
    }

    public function disableChangeable()
    {
        $this->changeAble = false;
    }

    public function functionToCommit($function, $token)
    {
        $this->token;
        $this->commitFunction = $function;
    }

    public function enableChangeable()
    {
        $this->changeAble = true;
    }

    public function setValue($text)
    {
        $this->value = $text;
    }

    public function getHTML()
    {
//        $this->genJSFunction();
        $html = "<input id='$this->fieldID' type='text' class='form-control' disabled='disabled' value='$this->value'/>";

        if ($this->changeAble) {
            $this->genJSFunction();
            $html = "       <div class='input-group'>
                            $html
                            <a id='$this->buttonID' class='input-group-addon btn btn-info'><span class='icon_pencil'></span></a>
                        </div>";
        }

        return $html;
    }

    private function genJSFunction()
    {
//        echo $this->jsID;
//        echo $this->fieldID . "<br>";
        InitJSObjects::initObject("$this->jsID", "EditTextField", "'$this->fieldID' , '$this->commitFunction' , '$this->token'");
//        EventHandler::newEvent("$this->fieldID", "function(){ alert('test') } ", 'click' );
        EventHandler::newEvent("$this->buttonID", "function(){ $this->jsID.enableField() } ", 'click');
        EventHandler::newEvent("$this->fieldID", "function(){ $this->jsID.disableField() } ", 'blur');
//        EventHandler::newEvent("$this->fieldID", "function(){ $this->jsID.disableFieldOnKey('13') } ", 'keypress');
//        EventHandler::newEvent()/
    }

    public function getJsID(){
        return $this->jsID;
    }

    public function getButtonID()
    {
        return $this->buttonID;
    }

    public function getFieldID()
    {
        return $this->fieldID;
    }

}