<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWellSampleListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('well_sample_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('well_id')->constrained('wells')->onUpdate('cascade')->onDelete('cascade');
            $table->string('sample_code')->nullable();
            $table->decimal('from', 10, 2)->nullable();
            $table->decimal('to', 10, 2)->nullable();
            $table->decimal('weight')->nullable();
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
        Schema::dropIfExists('well_sample_lists');
    }
}
