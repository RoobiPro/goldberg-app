<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssayDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assay_data', function (Blueprint $table) {
          $table->id();

          $table->morphs('assaydata');

          $table->timestamps();
          $table->float('from', 6, 4)->nullable();
          $table->float('to', 6, 4)->nullable();
          $table->float('azimuth', 6, 4)->nullable();
          $table->float('dip', 6, 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assay_data');
    }
}
