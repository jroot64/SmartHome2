<?php

/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 06.11.2015
 * Time: 23:28
 */
class Module
{
    public static function getModuleID($moduleName){
        $db = Database::getinstance();

        $db->addBound($moduleName);
        $db->addParam(':moduleName');

        $result = $db->query("SELECT id FROM module WHERE name = :moduleName");

        return $result[0]['id'];
    }
}