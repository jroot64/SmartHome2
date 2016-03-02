<?php
/**
 * The dropdown class manages to generate a dropdown element.
 */

class Dropdown {

    private $elements;
    private $id;


    /**
     * The addOption function is used to add an element to the dropdown menu that will be shown at the bottom of the
     * current dropdown menu.
     *
     * @param $option
     */

    function addOption($option){

        $new = count($this->elements);
        $this->elements[$new] = $option;
    }


    /**
     * The setObject function is used to give the dropdown an unique html id. That makes it possible to get the
     * value of the chosen item by an javascript function.
     *
     * @param $id
     */

    function setObject($id){
        $this->id = $id;
    }


    /**
     * The getHTML function generates the html code and returns the code.
     */

    function getHTML(){
        $options = "";
        $disabled = "";
        $id = $this->id;
        if($this->elements != null) {
            foreach( $this->elements as $element ){

                $html = "";
                $value = "";
//            var_dump($element);
                if (isset($element['value'])) {
                    $value = $element['value'];
                    $html = $element['html'];
                } else {
                    $value = "";
                    $html = $element;
                }

                $options .= "<option value='$value'>$html</option>";
            }
        }else{
            $disabled = 'disabled="disabled"';
        }

        $html = "
                    <select id='$id' class='form-control' $disabled>
                        $options
                    </select>
        ";
        return $html;
    }

    
    /**
     * The function isOptionNull is used to identify Dropdownmenus which are empty
     * and can be tagged with the html tag: "disabled='disabled'".
     */

    function isOptionNull(){
//        var_dump($this->elements);
//        if($this->elements != null){
//            return true;
//        }else{
//            return false;
//        }
        return $this->elements != null;
    }
}