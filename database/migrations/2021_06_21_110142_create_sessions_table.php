<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->boolean('active');
            $table->integer('duration')->nullable();
            $table->timestamp('last_alive')->nullable();
            $table->string('session_string', 32)->nullable(); // Add the session_string column
            $table->text('payload'); // Add the payload column
            $table->integer('last_activity'); // Add the last_activity column
            $table->string('ip_address', 45)->nullable(); // Add the ip_address column
            $table->text('user_agent')->nullable(); // Add the user_agent column
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
        Schema::dropIfExists('sessions');
    }
}
