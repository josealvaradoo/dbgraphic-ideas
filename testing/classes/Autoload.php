<?php

namespace Classes;

class Autoload
{
    public static function exec()
    {
        spl_autoload_register(function($class) {
            $link = "./" . str_replace("\\", "/", $class) . ".php";
            if(is_readable($link)) {
                include_once $link;
            }
        });
    }
}