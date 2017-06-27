<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateProgressBatchesTable.
 */
class CreateProgressBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress_batches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('accomplished')->unsigned()->nullable();
            $table->integer('incidences')->unsigned()->nullable();
            $table->string('state');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('progress_batches');
    }
}
