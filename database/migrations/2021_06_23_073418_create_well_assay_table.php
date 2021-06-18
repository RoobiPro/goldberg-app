<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWellAssayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('well_assays', function (Blueprint $table) {
          $table->id();
          $table->foreignId('well_id')->constrained('wells')->onUpdate('cascade')->onDelete('cascade');
          // $table->foreignId('sample_list_id')->nullable()->references('id')->on('sample_lists');
          $table->string('sample')->nullable();
          $table->string('analysis_code')->nullable();
          $table->string('laboratory')->nullable();
          $table->string('certificate_number')->nullable();
          $table->date('date_sent')->nullable();
          $table->date('date_received')->nullable();
          $table->decimal('from', 10, 2)->nullable();
          $table->decimal('to', 10, 2)->nullable();
          $table->decimal('element_Fe', 10, 2)->nullable();
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
        Schema::dropIfExists('well_assays');
    }
}
