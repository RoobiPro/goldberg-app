<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLithologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lithologies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_list_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('utm_x', 10, 2)->nullable();
            $table->decimal('utm_y', 10, 2)->nullable();
            $table->decimal('utm_z', 10, 2)->nullable();
            $table->decimal('from', 10, 2)->nullable();
            $table->decimal('to', 10, 2)->nullable();
            $table->longText('rock_description')->nullable();
            $table->string('geological_unit_code')->nullable();
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
        Schema::dropIfExists('lithology_data');
    }
}
