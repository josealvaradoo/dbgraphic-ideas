<?php

require('config/core.php');

$xml = new Classes\Xml;

// Xml
$xml->file('output.xml');
$x = $xml->load();

// Migrations
foreach($xml->get() as $m) {
    // Template
    $template = new Classes\Template;
    $template->file('templates/migration.tpl');

    // Migration
    $migration = new Classes\Migration($m);
    $migration->setMigrationTable();
    $migration->setTemplate($template);
    $content = "";
    $relations = [];

    foreach($m[0] as $rows) {
        // Row
        $row = new Classes\MigrationRow($rows);
        $row->format();
        $content .= $row->insert();
        if($row->hasRelation()) {
            $relations[] = $row->hasRelation();
        }
    }

    // Render Migration
    $migration->fillContent($content);
    $migration->create();
    
    // Download File
    // $migration->download($migration->getMigrationTable());
}