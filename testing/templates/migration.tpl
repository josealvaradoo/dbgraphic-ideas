<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class {{name}} extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
{{up}}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
{{down}}
    }
}

?>