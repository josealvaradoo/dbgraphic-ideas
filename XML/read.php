<?php
// Var
$NAME_OF_MIGRATION = "CreateUsersTable";
$CONTENT_OF_MIGRATION = "";
$t = "table";
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
//$fileString = str_replace("{{name}}", $NAME_OF_MIGRATION, $fileString);

// var_dump($xml);

function name($name) {
  return strtolower($name);
}


foreach($xml as $tables) {
  $table_name = name($tables->attributes()->name);
  $capitalized_table_name = ucwords($table_name);
  $className = "Create{$capitalized_table_name}Table";
  $fileString = str_replace("{{name}}", $className, $fileString);

  $CONTENT_OF_MIGRATION .= "\t\tSchema::create('{$table_name}', function (Blueprint, $".$t.") { \n";

    echo(count($tables));

    foreach($tables->row as $rows) {

      $column_name = name($rows->attributes()->name);

      if ($rows->attributes()->autoincrement == 1 AND $rows->datatype == 'INTEGER') {
        $CONTENT_OF_MIGRATION .= "\t\t\t$".$t."->increments('{$column_name}');\n";
      }
      elseif ($rows->attributes()->autoincrement == 0 AND $rows->datatype == 'INTEGER') {
        if ($rows->relation) {
          // Foreign Key Constraints
          $CONTENT_OF_MIGRATION .= "\t\t\t$".$t."->integer('{$column_name}')->unsigned();\n";
        }
        else {
          // CUANDO UNICAMENTE ES UN VALOR integer
          $CONTENT_OF_MIGRATION .= "\t\t\t$".$t."->integer('{$column_name}');\n";
        }
      }
      elseif ($rows->attributes()->autoincrement == 0) {
        $CONTENT_OF_MIGRATION .= "\t\t\t$".$t."->string('{$column_name}');\n";
      }
    }

    foreach($tables->row as $rows) {
      if($rows->attributes()->autoincrement == 0 AND $rows->datatype == 'INTEGER' AND $rows->relation) {
        $column_name = name($rows->attributes()->name);
        $reference = name($rows->relation->attributes()->row);
        $on = name($rows->relation->attributes()->table);
        $CONTENT_OF_MIGRATION .= "\n";
        $CONTENT_OF_MIGRATION .= "\t\t\t$".$t."->foreign('{$column_name}')->references('{$reference}')->on('{$on}');\n";
      }
    }

  // foreach($xml->prueba[0]->attributes() as $a => $b) {
  //   echo $a,'="',$b,"\"\n";
  // }

  $CONTENT_OF_MIGRATION .= "\t\t}\n";
  $LIST_TABLES_DOWN .= "\t\tSchema::dropIfExists('{$table_name}');\n";
}

$fileString = str_replace("{{rows}}", $CONTENT_OF_MIGRATION, $fileString);
$fileString = str_replace("{{tables}}", $LIST_TABLES_DOWN, $fileString);


// Print
echo /*htmlspecialchars*/($fileString);

// Close file template.tpl
fclose($myFile);
