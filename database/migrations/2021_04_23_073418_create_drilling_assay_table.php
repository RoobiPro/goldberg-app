<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrillingAssayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drilling_assays', function (Blueprint $table) {
          $table->id();
          $table->foreignId('drilling_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
          $table->foreignId('sample_list_id')->nullable()->references('id')->on('sample_lists');
          $table->string('sample')->nullable();
          $table->decimal('from', 10, 2)->nullable();
          $table->decimal('to', 10, 2)->nullable();
          $table->string('certificate')->nullable();
          $table->decimal('element_Ag', 10, 2)->nullable();
          $table->decimal('element_Al', 10, 2)->nullable();
          $table->decimal('element_As', 10, 2)->nullable();
          $table->decimal('element_Au', 10, 2)->nullable();
          $table->decimal('element_B', 10, 2)->nullable();
          $table->decimal('element_Ba', 10, 2)->nullable();
          $table->decimal('element_Be', 10, 2)->nullable();
          $table->decimal('element_Bi', 10, 2)->nullable();
          $table->decimal('element_Ca', 10, 2)->nullable();
          $table->decimal('element_Cd', 10, 2)->nullable();
          $table->decimal('element_Ce', 10, 2)->nullable();
          $table->decimal('element_Co', 10, 2)->nullable();
          $table->decimal('element_Cr', 10, 2)->nullable();
          $table->decimal('element_Cs', 10, 2)->nullable();
          $table->decimal('element_Cu', 10, 2)->nullable();
          $table->decimal('element_Fe', 10, 2)->nullable();
          $table->decimal('element_Ga', 10, 2)->nullable();
          $table->decimal('element_Ge', 10, 2)->nullable();
          $table->decimal('element_Hf', 10, 2)->nullable();
          $table->decimal('element_Hg', 10, 2)->nullable();
          $table->decimal('element_In', 10, 2)->nullable();
          $table->decimal('element_K', 10, 2)->nullable();
          $table->decimal('element_La', 10, 2)->nullable();
          $table->decimal('element_Li', 10, 2)->nullable();
          $table->decimal('element_Mg', 10, 2)->nullable();
          $table->decimal('element_Mn', 10, 2)->nullable();
          $table->decimal('element_Mo', 10, 2)->nullable();
          $table->decimal('element_Na', 10, 2)->nullable();
          $table->decimal('element_Nb', 10, 2)->nullable();
          $table->decimal('element_Ni', 10, 2)->nullable();
          $table->decimal('element_P', 10, 2)->nullable();
          $table->decimal('element_Pb', 10, 2)->nullable();
          $table->decimal('element_Rb', 10, 2)->nullable();
          $table->decimal('element_Re', 10, 2)->nullable();
          $table->decimal('element_S', 10, 2)->nullable();
          $table->decimal('element_Sb', 10, 2)->nullable();
          $table->decimal('element_Sc', 10, 2)->nullable();
          $table->decimal('element_Se', 10, 2)->nullable();
          $table->decimal('element_Sn', 10, 2)->nullable();
          $table->decimal('element_Sr', 10, 2)->nullable();
          $table->decimal('element_Ta', 10, 2)->nullable();
          $table->decimal('element_Te', 10, 2)->nullable();
          $table->decimal('element_Th', 10, 2)->nullable();
          $table->decimal('element_Ti', 10, 2)->nullable();
          $table->decimal('element_Tl', 10, 2)->nullable();
          $table->decimal('element_U', 10, 2)->nullable();
          $table->decimal('element_V', 10, 2)->nullable();
          $table->decimal('element_W', 10, 2)->nullable();
          $table->decimal('element_Y', 10, 2)->nullable();
          $table->decimal('element_Zn', 10, 2)->nullable();
          $table->decimal('element_Zr', 10, 2)->nullable();
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
        Schema::dropIfExists('drilling_assays');
    }
}
