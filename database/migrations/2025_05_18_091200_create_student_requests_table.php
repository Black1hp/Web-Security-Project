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
        Schema::create('student_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('request_type'); // course_withdrawal, grade_change, retake_exam, etc.
            $table->string('reference_type')->nullable(); // course, grade, etc.
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of the referenced entity
            $table->text('reason');
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, in_review
            $table->json('approval_workflow')->nullable(); // JSON structure for approval steps
            $table->json('approval_history')->nullable(); // JSON structure for approval history
            $table->foreignId('current_approver_id')->nullable()->constrained('users');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_requests');
    }
};
