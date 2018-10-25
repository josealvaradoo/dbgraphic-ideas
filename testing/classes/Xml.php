<?php

namespace Classes;

use Classes\Template;

class Xml
{
    private $file;
    private $fileName;

    public function __construct()
    {
        //
    }

    public function file($file)
    {
        $this->file = $file;
        $this->fileName = $file;
    }

    public function load()
    {
        $this->file = simplexml_load_file($this->file) or die("Error: Cannot create object");
    }

    public function get()
    {
        return $this->file;
    }

    public function getInputXml()
    {
        return $this->fileName;
    }

    public function __destruct()
    {
        unset($this->file);
    }
}