<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLithologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lithologies', function (Blueprint $table) {
            $table->id();
            $table->morphs('lithologyable');
            $table->foreignId('sample_list_id')->nullable()->references('id')->on('sample_lists');
            $table->decimal('from', 10, 2)->nullable();
            $table->decimal('to', 10, 2)->nullable();
            $table->longText('rock_code')->nullable();
            $table->longText('rock_description')->nullable();
            $table->string('geological_unit')->nullable();
            $table->integer('csv_import_id')->nullable();
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
        Schema::dropIfExists('lithology_data');
    }
}
