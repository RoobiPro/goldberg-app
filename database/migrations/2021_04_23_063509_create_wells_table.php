<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wells', function (Blueprint $table) {
          $table->id();
          $table->unsignedInteger('campaign_id')->references('id')->on('projects')
                ->onDelete('cascade');
          $table->decimal('coordinates_x', 10, 7)->nullable();
          $table->decimal('coordinates_y', 10, 7)->nullable();
          $table->float('coordinates_z', 6, 4)->nullable();
          $table->float('dip', 6, 4)->nullable();
          $table->float('azimuth', 6, 4)->nullable();
          $table->float('length', 6, 4)->nullable();
          $table->string('drilling_type')->nullable();
          $table->timestamp('start_date')->nullable();
          $table->timestamp('end_date')->nullable();
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
        Schema::dropIfExists('wells');
    }
}
