<?php

namespace Classes;

use Classes\Template;

class Migration
{
    private $migration;
    private $table;
    private $name;
    private $template;

    public function __construct($migration = null)
    {
        $this->init($migration);
    }

    public function init($migration)
    {
        $this->migration = $migration;
    }

    public function setTemplate(Template $template)
    {
        $this->template = $template;
    }

    public function setMigrationTable($name=null)
    {
        if(!$name) {
            $this->table = $this->get('name');
        }
        else {
            $this->table = strtolower($name);
        }
    }

    public function getMigrationTable()
    {
        return $this->table;
    }

    public function setMigrationName()
    {
        $capitalized = ucwords($this->table);
        $this->name = "Create{$capitalized}Table";
        
        $this->template->write('name', $this->name);
    }
    
    public function getMigrationName()
    {
        return $this->name;
    }

    public function get($something=null)
    {
        if(!$something) {
            return $this->migration;
        }

        return strtolower($this->migration->attributes()->$something);
    }

    public function fillContent($content)
    {
        $this->content = $content;
    }

    private function up()
    {
        $table = $this->table;
        $content = "";
        $content .= "\t\tSchema::create('{$table}', function (Blueprint \$table) {\n";
        $content .= $this->content;
        $content .= "\t\t});\n";

        $this->template->write('up', $content);
    }

    private function down()
    {
        $content = "\t\tSchema::dropIfExists('{$this->table}');";
        
        $this->template->write('down', $content);
    }

    public function create()
    {
        $this->template->open();
        $this->template->read();
        $this->setMigrationName();
        $this->up();
        $this->down();
        $this->template->render();
        $this->template->close();
    }

    public function download()
    {
        $this->template->download($this->table);
    }
}