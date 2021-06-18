<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrillingMineralizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drilling_mineralizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drilling_id')->constrained('drillings')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreignId('sample_list_id')->nullable()->references('id')->on('sample_lists');
            $table->decimal('from', 10, 2)->nullable();
            $table->decimal('to', 10, 2)->nullable();
            $table->integer('intensity')->nullable();
            $table->longText('mineralization_code')->nullable();
            $table->longText('mineralization_description')->nullable();
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
        Schema::dropIfExists('mineralization_data');
    }
}
