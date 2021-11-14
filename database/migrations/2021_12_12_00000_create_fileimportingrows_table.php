<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileimportingrowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fileimportations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('controller', 512)->nullable();
            $table->string('temporary_filepath', 512)->nullable();

            $table->text('request_data')->nullable();

            $table->string('filename');
            $table->integer('rows_count')->nullable();

            $table->timestamp('storing_started_at')->nullable();
            $table->timestamp('storing_ended_at')->nullable();
            $table->timestamp('parsing_started_at')->nullable();
            $table->timestamp('parsing_keep_at')->nullable();
            $table->timestamp('parsing_ended_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('fileimportationrows', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('fileimportation_id')->nullable();
            $table->foreign('fileimportation_id')->references('id')->on('fileimportations');

            $table->text('data')->nullable();

            $table->boolean('parsed')->nullable();
            $table->string('parsed_code', 12)->nullable();
            $table->timestamp('parsed_at')->nullable();
            $table->text('parsing_notes')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('fileimportationrows');
        Schema::dropIfExists('fileimportations');


    }
}
