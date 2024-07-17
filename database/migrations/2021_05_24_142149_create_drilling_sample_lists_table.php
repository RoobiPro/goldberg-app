<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrillingSampleListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drilling_sample_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drilling_id')->constrained('drillings')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->string('sample_code')->nullable();
            $table->decimal('from', 10, 2)->nullable();
            $table->decimal('to', 10, 2)->nullable();
            $table->string('sample_type')->nullable();
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
        Schema::table('drilling_sample_lists', function (Blueprint $table) {
            if (Schema::hasColumn('drilling_sample_lists', 'drilling_id')) {
                $table->dropForeign(['drilling_id']);
            }
            if (Schema::hasColumn('drilling_sample_lists', 'sample_list_id')) {
                $table->dropForeign(['sample_list_id']);
            }
        });

        Schema::dropIfExists('drilling_sample_lists');
    }
}
