<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drillings', function (Blueprint $table) {
            $table->id();


            $table->foreignId('project_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->decimal('coordinates_x', 10, 7)->nullable();
            $table->decimal('coordinates_y', 10, 7)->nullable();
            $table->decimal('coordinates_z', 6, 4)->nullable();
            $table->decimal('dip', 6, 4)->nullable();
            $table->decimal('azimuth', 6, 4)->nullable();
            $table->decimal('length', 6, 4)->nullable();
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
        Schema::dropIfExists('drillings');
    }
}
