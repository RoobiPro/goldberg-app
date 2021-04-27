<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_data', function (Blueprint $table) {
            $table->id();
            $table->morphs('surveydata');
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
        Schema::dropIfExists('survey_data');
    }
}
