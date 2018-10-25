<?php

namespace Classes;

class MigrationRow
{
    private $row;
    private $name;
    private $type;
    private $default;
    private $relation;
    private $nullable;
    private $unsigned;
    private $autoincrement;

    public function __construct($row)
    {
        $this->row = $row;
        $this->set();
    }

    public function get($attribute=null)
    {
        if(!$attribute) {
            return $this->row;
        }
        return strtolower($this->row->attributes()->attribute);
    }

    public function isRow()
    {
        return !array_key_exists('part', (array) $this->row);
    }

    private function set()
    {
        if($this->isRow()) {
            $this->name = strtolower($this->row->attributes()[0]);
            $this->type = strtolower($this->row->datatype);
            $this->nullable = filter_var($this->row->attributes()[1]);
            $this->default = strtolower($this->row->default);
            $this->relation = strtolower($this->row->relation);
            $this->autoincrement = filter_var($this->row->attributes()[2]);
            $this->unsigned = filter_var($this->row->attributes()[3]) ?? 0;

            // Convert to Object
            $this->format();
        }
    }

    public function format()
    {
        $this->row->{'name'} = $this->name;
        $this->row->{'type'} = $this->type;
        $this->row->{'nullable'} = $this->nullable === "1" ? true : false;
        $this->row->{'default'} = $this->default;
        $this->row->{'relation'} = $this->relation;
        $this->row->{'autoincrement'} = $this->autoincrement === "1" ? true : false;
        $this->row->{'unsigned'} = $this->autoincrement === "1" ? true : false;
    }

    public function hasRelation()
    {
        if($this->relation AND $this->relation['table']) {
            return [
                'field' => $this->name,
                'on' => strtolower($this->relation['table']),
                'reference' => strolower($this->relation['row'])
            ];
        }
        
        return false;
    }

    public function insert()
    {
        $sentence = "\t\t\t\$table";
        if($this->isRow()) {
            $sentence .= "->{$this->type}('{$this->name}')";

            if($this->autoincrement === "1") {
                $sentence .= "->increments('{$this->name}')";
            }

            if(substr($this->type, 0, 7) === "varchar") {
                $type = "string";
                $chars = substr($this->type, 8, -1);
                if($chars > 0) {
                    $sentence = "\t\t\t\$table->{$type}('{$this->name}', {$chars})";
                }
                else {
                    $sentence = "\t\t\t\$table->{$type}('{$this->name}')";
                }
            }

            if($this->nullable === "1" AND $this->autoincrement === "0") {
                $sentence .= "->nullable()";
            }

            if($this->unsigned === "1" AND $this->autoincrement === "0") {
                $sentence .= "->unsigned()";
            }

            if($this->default !== "null") {
                $sentence .= "->default('{$this->default}')";
            }

            if($this->relation AND $this->relation['table']) {
                $sentence .= "->unsigned()";
            }

            $sentence .= ";\n";

            return $sentence;
        }
    }
}