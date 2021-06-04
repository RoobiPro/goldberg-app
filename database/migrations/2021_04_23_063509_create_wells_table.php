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
          $table->foreignId('project_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
          $table->string('well_code')->nullable();
          $table->string('well_campaign')->nullable();
          $table->string('type')->nullable();
          $table->decimal('utm_x', 10, 2)->nullable();
          $table->decimal('utm_y', 10, 2)->nullable();
          $table->decimal('utm_z', 10, 2)->nullable();
          $table->timestamp('date')->nullable();
          $table->decimal('dip', 10, 2)->nullable();
          $table->decimal('length', 10, 2)->nullable();
          $table->decimal('azimuth', 10, 2)->nullable();
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
        Schema::dropIfExists('wells');
    }
}
