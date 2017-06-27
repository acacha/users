<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUserMigrationsTable.
 */
class CreateUserMigrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_migrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source_user_id')->unsigned()->nullable();
            $table->json('source_user');
            $table->boolean('done')->default(true);
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('user_migrations_batch_id')->unsigned()->nullable();
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
        Schema::dropIfExists('user_migrations');
    }
}
