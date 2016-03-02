<?php

/**
 * The class panel is used to make it easyer creating a panel and to avoid mistakes
 * during the process.
 */
class Panel
{

    private $content;
    private $title;
    private $size;
    private $panelID;
    private $bodyID;
    private $headID;
    private $buttonID;


    /**
     * The function __construct is used to clear the class variables.
     */

    function __construct()
    {
        $this->content = "";
        $this->title = "";
        $this->size = "";
        $this->panelID = DOM_id::getID();
        $this->bodyID = DOM_id::getID();
        $this->headID = DOM_id::getID();
        $this->buttonID = DOM_id::getID();
    }


    /**
     * The function panel makes it possible to create a panel using one call and giving the
     * the title, content and the size. The returned code is the html code that can be displayed on the page.
     *
     * @param $title
     * @param $content
     * @param $size
     * @return string
     */

    public static function panel($title, $content, $size)
    {
        $self = new Panel();

        $self->setSize($size);
        $self->setTitle($title);
        $self->addContent($content);

        $html = $self->getHTML();

        return $html;

    }


    /**
     * The function setSize is used to save the size of the panel that was returned.
     *
     * @param $size
     */

    function setSize($size)
    {
        $this->size = $size;
    }


    /**
     * The function setTitle expects the title as a string. The title was saved at an class variable.
     *
     * @param $title
     */

    function setTitle($title)
    {
        $this->title = $title;
    }


    /**
     * The function addContent expects a string of content that is shown in the panel
     *
     * @param $content
     */

    function addContent($content)
    {
        $this->content .= $content;
    }


    /**
     * The getHTML function creates the html panel code based on the content, size and the title.
     *
     * @return string
     */

    function getHTML()
    {
        $show  = true;
        $HTML = "
                    <div class='$this->size own-padding'>
                        <div class='panel col-sm-12'>
                            <header id='$this->headID' class='panel-heading'>
                                $this->title
                                <div class='panel-actions'>
                                    <a id='$this->buttonID' class='btn-minimize'>
                                        <i  class='fa'><span class='arrow_up'/></i>
                                    </a>
                                </div>
                            </header>

                            <div id='$this->bodyID' class='panel-body'>
                                $this->content
                            </div>
                        </div>
                    </div>
";


        if( $this->content == ""){
            $HTML = "";
            Errorlist::publicAddError(4,1);
            $show = false;
        }

        if( $this->title == ""){
            Errorlist::publicAddError(5,1);
        }

        if( $this->size == ""){
            Errorlist::publicAddError(6,1);
            $HTML = "";
            $show = false;
        }

        if($show){
            InitJSObjects::initObject($this->panelID, 'Panel', "'$this->headID','$this->bodyID','$this->buttonID'");
            EventHandler::newEvent("$this->buttonID", "function(){ $this->panelID.minMax() }", "click");
        }


        return $HTML;
    }
}