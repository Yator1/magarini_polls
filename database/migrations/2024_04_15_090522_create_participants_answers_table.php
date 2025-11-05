<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::create('participants_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participant_id');
            // Assuming participant_id references a users table
            $table->foreign('participant_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('poll_id');
            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
            $table->unsignedBigInteger('poll_question_id');
            $table->foreign('poll_question_id')->references('id')->on('poll_questions')->onDelete('cascade');
            $table->unsignedBigInteger('answer_id');
            $table->foreign('answer_id')->references('id')->on('poll_answers')->onDelete('cascade');
            $table->string('status', 255)->default(1);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants_answers');
    }
};
