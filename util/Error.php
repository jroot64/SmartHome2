<?php

/**
 * The error class represents an error and contains the elements that defines an error.
 */
//TODO add commend error class
class Error
{

    private $errorCode;
    private $errorLevel;
    private $errorContent;


    /**
     * The constructor loads the error elements based on the errorcode and the module id.
     *
     * @param $eCode
     */

    function __construct($eCode, $mod_id, $id = true)
    {
        //TODO  load content from database implement the module id as the second base of the error identification

        $db = Database::getinstance();
        if (!($id === true)) {

            $db->addBound($id);
            $db->addParam('id');

            $result = $db->query('SELECT * FROM error_code WHERE id = :id;');

            $eCode = $result[0]['ecode'];
            $mod_id = $result[0]['module_id'];
        }

        $db->addBound($eCode);
        $db->addParam('eCode');
        $db->addBound($mod_id);
        $db->addParam('mod_id');

        $result = $db->query("SELECT * FROM error_code WHERE ecode = :eCode AND module_id = :mod_id");

        if (!$result) {
            $result[0]['id'] = "0";
            $result[0]['level'] = "1";
            $result[0]['text'] = "Es sollte ein Fehler erzeugt werden der nicht in der Datenbank abgelegt wurde.
                                  <br> Errorcode: $eCode
                                  <br> von Modul: $mod_id";
        }
//        var_dump($result);
//        echo "SELECT * FROM error_code WHERE ecode = $eCode AND module_id = $mod_id";
        $this->errorCode = $result[0]['id'];
        $this->errorLevel = $result[0]['level'];
        $this->errorContent = $result[0]['text'];
    }


    /**
     * The function returns the level of the error.
     */

    function getLevel()
    {
        return $this->errorLevel;
    }


    /**
     * The function getHTML generates the html code for the error based on the level of the error.
     *
     * @return string
     */

    function getHTML()
    {
        $classColor = "";
        $classImage = "";
        $text = "";

        switch ($this->errorLevel) {
            case 1:
                $text = "Error";
                $classColor = "alert alert-danger";
                $classImage = "icon_error-triangle_alt";
                break;
            case 2:
                $text = "Warning";
                $classColor = "alert alert-warning";
                $classImage = "icon_error-circle_alt";
                break;
            case 3:
                $text = "Info";
                $classColor = "alert alert-info";
                $classImage = "icon_info_alt";
                break;
        }

//        $table = new Table();
//        $table->setTableColumns(2);
//
//        $table->addTableContent("<span class='$classImage own-padding' aria-hidden='true'></span>" . $text . ": " . $this->errorLevel);
//        $table->addTableContent($this->errorContent);

//        $content = $table->getHTML();

        $html = "   <div class='$classColor own-padding' role='alert'>
                        <div class='own-padding'>
                        <b>
                            <span class='$classImage own-padding' aria-hidden='true'></span>
                                $text:  $this->errorLevel
                        </b>
                        $this->errorContent
                        </div>
                    </div>";

        return $html;

    }

    public function getID()
    {
        return $this->errorCode;
    }
}