<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assays', function (Blueprint $table) {
          $table->id();
          $table->foreignId('sample_list_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
          $table->decimal('utm_x', 10, 2)->nullable();
          $table->decimal('utm_y', 10, 2)->nullable();
          $table->decimal('utm_z', 10, 2)->nullable();
          $table->decimal('from', 10, 2)->nullable();
          $table->decimal('to', 10, 2)->nullable();
          $table->string('analysis_code')->nullable();
          $table->string('laboratory')->nullable();
          $table->string('certificate_number')->nullable();
          $table->date('date_sent')->nullable();
          $table->date('date_received')->nullable();
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
        Schema::dropIfExists('assay_data');
    }
}
