<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_lists', function (Blueprint $table) {
            $table->id();
            $table->string('listabel_campaign_type')->nullable();
            $table->integer('listabel_campaign_id')->nullable();
            $table->string('listabel_data_type')->nullable();
            $table->integer('listabel_data_id')->nullable();
            // $table->morphs('listabel_data')->nullable();
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
        Schema::dropIfExists('sample_lists');
    }
}
