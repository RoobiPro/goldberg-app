<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandSampleSampleListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hand_sample_sample_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hand_sample_id')->constrained('hand_samples')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->string('hand_sample_code')->nullable();
            $table->decimal('utm_x', 10, 2)->nullable();
            $table->decimal('utm_y', 10, 2)->nullable();
            $table->decimal('utm_z', 10, 2)->nullable();
            $table->integer('weight')->nullable();
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
        Schema::table('hand_sample_sample_lists', function (Blueprint $table) {
            if (Schema::hasColumn('hand_sample_sample_lists', 'hand_sample_id')) {
                $table->dropForeign(['hand_sample_id']);
            }
            if (Schema::hasColumn('hand_sample_sample_lists', 'sample_list_id')) {
                $table->dropForeign(['sample_list_id']);
            }
        });

        Schema::dropIfExists('hand_sample_sample_lists');
    }
}
