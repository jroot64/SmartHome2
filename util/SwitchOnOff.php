<?php

/**
 * Each object of SwitchOnOff represents and manages a toggle switches
 */
class SwitchOnOff
{

    private $idOn;
    private $idOff;
    private $disabled = false;

    private $status;


    /**
     * Returns the HTML for the Switch, depending on status and disabled
     */

    public function getHTML()
    {
        $classSuccess = "";
        $classDanger = "";

        $idOn = $this->idOn;
        $idOff = $this->idOff;

        if ($this->status == 1) {
            $classSuccess = "active";
        } elseif ($this->status == 0) {
            $classDanger = "active";
        }

        if ($this->disabled) {
            $disabled = 'disabled="disabled"';
        } else {
            $disabled = "";
        }

        $switch = "
                    <label id='$idOn' class='btn btn-success btn-sm $classSuccess' $disabled>
                        On
                    </label>
                    <label id='$idOff' class='btn btn-danger btn-sm $classDanger' $disabled>
                        Off
                    </label>
";

        $html = "<div class='btn-group btn-sm'> $switch </div>";

        return $html;
    }


    /**
     * Sets Variables
     *
     * @param $status
     * @param $idOn
     * @param $idOff
     */

    public function setObject($status, $idOn, $idOff){
        $this->status = $status;
        $this->idOff = $idOff;
        $this->idOn = $idOn;
    }


    /**
    *  Disables the switch
    */

    public function setDisabled()
    {
        $this->disabled = true;
    }


    /**
    *  Enables the switch
    */

    public function setEnabled()
    {
        $this->disabled = false;
    }
}