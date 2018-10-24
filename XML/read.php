<?php
// Var
$NAME_OF_MIGRATION = "NAME_OF_MIGRATION";
$CONTENT_OF_MIGRATION = "";
// Schema::create('<NAME_OF_MIGRATION>', function (Blueprint $table) {
//     $table->increments('id');
//     $table->string('name');
//     $table->string('airline');
//     $table->timestamps();
// });
$LIST_TABLES_DOWN = "";
// Schema::drop('flights');


// Open file template.tpl
$myFile = fopen("template.tpl", "r") or die("Unable to open file!");
$fileString = fread($myFile, filesize("template.tpl"));

// Open XML
$xml = simplexml_load_file("db.xml") or die("Error: Cannot create object");
// var_dump($xml);

// Edit tpl
$fileString = str_replace("<NAME_OF_MIGRATION>", $NAME_OF_MIGRATION, $fileString);

// var_dump($xml);


foreach ($xml as $tables) {

  $CONTENT_OF_MIGRATION .= "        Schema::create('".$tables->attributes()->name."', function (Blueprint " . '$table' . "  ) { \n";

    foreach ($tables->row as $rows) {
      if ($rows->attributes()->autoincrement == 1 AND $rows->datatype == 'INTEGER') {
        $CONTENT_OF_MIGRATION .= "          " . '$table' . "->increments('".$rows->attributes()->name."'); \n";
      } elseif ($rows->attributes()->autoincrement == 0 AND $rows->datatype == 'INTEGER') {
        $CONTENT_OF_MIGRATION .= "          " . '$table' . "->integer('".$rows->attributes()->name."'); \n";
      } elseif ($rows->attributes()->autoincrement == 0) {
        $CONTENT_OF_MIGRATION .= "          " . '$table' . "->string('".$rows->attributes()->name."'); \n";
      }
    }

  // foreach($xml->prueba[0]->attributes() as $a => $b) {
  //   echo $a,'="',$b,"\"\n";
  // }

  $CONTENT_OF_MIGRATION .= "        }\n";

  $LIST_TABLES_DOWN .= "        Schema::drop('".$tables->attributes()->name."'); \n";
}

$fileString = str_replace("<CONTENT_OF_MIGRATION>", $CONTENT_OF_MIGRATION, $fileString);
$fileString = str_replace("<LIST_TABLES_DOWN>", $LIST_TABLES_DOWN, $fileString);


// Print
echo /*htmlspecialchars*/($fileString);

// Close file template.tpl
fclose($myFile);
