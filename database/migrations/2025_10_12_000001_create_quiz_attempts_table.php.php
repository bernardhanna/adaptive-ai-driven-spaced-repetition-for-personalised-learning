<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('question_id');
            $table->float('confidence_score')->nullable();
            $table->boolean('showed_answer')->default(false);
            $table->boolean('skipped')->default(false);
            $table->integer('time_taken')->comment('Time taken in seconds');
            $table->boolean('completed')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['user_id']);
            $table->dropForeign(['quiz_id']);
            $table->dropForeign(['question_id']);
        });

        // Now that the foreign keys have been dropped, it's safe to drop the table
        Schema::dropIfExists('quiz_attempts');
    }
};
