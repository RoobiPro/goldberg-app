<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMineralizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mineralizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_list_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('intensity')->nullable();
            $table->decimal('utm_x', 10, 2)->nullable();
            $table->decimal('utm_y', 10, 2)->nullable();
            $table->decimal('utm_z', 10, 2)->nullable();
            $table->decimal('from', 10, 2)->nullable();
            $table->decimal('to', 10, 2)->nullable();
            $table->longText('mineralization_description')->nullable();
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
