<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssayElementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assay_element', function (Blueprint $table) {
          $table->foreignId('assay_data_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
          $table->foreignId('element_id')->consrained();
          $table->decimal('ppm', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assay_element');
    }
}
