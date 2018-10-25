<?php

namespace Classes;

class Template
{
    private $file;
    private $template;
    private $fileString;

    public function __construct()
    {
        //
    }

    public function file($file) 
    {
        $this->file = $file;
    }

    public function open()
    {
        $this->template = fopen($this->file, "r") or die("Unable to open file!");
    }

    public function read()
    {
        $this->fileString = fread($this->template, filesize($this->file));
    }

    public function write($section, $content)
    {
        $this->fileString = str_replace("{{{$section}}}", $content, $this->fileString);
    }

    public function close()
    {
        $this->fileString = fclose($this->template);
    }

    public function render()
    {
        if($this->template) {
            echo $this->fileString;
        }
    }

    public function getTemplate()
    {
        return $this->file;
    }

    public function download($title)
    {
        if($this->template) {
            $date = date('Y_m_d_his');
            $title = "create_{$title}_table.php";
            $filename = "{$date}_{$title}";
            
            header("Content-Disposition: attachment; filename={$filename}");
            header('Content-Transfer-Encoding: binary');
        }
    }

    public function __destruct()
    {
        unset($this->file);
        unset($this->template);
        unset($this->fileString);
    }
}