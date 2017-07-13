<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateProgressBatchesTable.
 */
class CreateGoogleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customerId');
            $table->string('kind');
            $table->integer('google_id')->unsigned()->nullable();
            $table->string('etag');
            $table->string('primaryEmail')->unique();
            $table->string('givenName');
            $table->string('familyName');
            $table->string('fullName');
            $table->string('orgUnitPath');
            $table->json('organizations')->nullable();
            $table->boolean('isAdmin');
            $table->boolean('isDelegatedAdmin');
            $table->dateTime('lastLoginTime');
            $table->dateTime('creationTime');
            $table->dateTime('deletionTime')->nullable();
            $table->dateTime('agreedToTerms');
            $table->string('password')->nullable();
            $table->string('hashFunction')->nullable();
            $table->boolean('suspended');
            $table->string('suspensionReason')->nullable();
            $table->boolean('changePasswordAtNextLogin');
            $table->json('emails');
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
        Schema::dropIfExists('google_users');
    }
}
