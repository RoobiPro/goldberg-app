<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlterationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alterations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drilling_id')->constrained('drillings')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('sample_list_id')->nullable()->references('id')->on('sample_lists');
            $table->integer('intensity')->nullable();
            $table->decimal('utm_x', 10, 2)->nullable();
            $table->decimal('utm_y', 10, 2)->nullable();
            $table->decimal('utm_z', 10, 2)->nullable();
            $table->decimal('from', 10, 2)->nullable();
            $table->decimal('to', 10, 2)->nullable();
            $table->string('alteration_code')->nullable();
            $table->longText('alteration_description')->nullable();
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
        Schema::dropIfExists('alteration_data');
    }
}
