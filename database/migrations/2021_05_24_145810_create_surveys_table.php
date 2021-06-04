<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->morphs('suveryable');
            $table->string('type')->nullable();
            $table->decimal('from', 10, 2)->nullable();
            $table->decimal('to', 10, 2)->nullable();
            $table->decimal('azimuth', 10, 2)->nullable();
            $table->decimal('dip', 10, 2)->nullable();
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
        Schema::dropIfExists('surveys');
    }
}
