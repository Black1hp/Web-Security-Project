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
        Schema::create('academic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('semester');
            $table->decimal('semester_gpa', 3, 2)->nullable();
            $table->decimal('cumulative_gpa', 3, 2)->nullable();
            $table->integer('credits_attempted');
            $table->integer('credits_earned');
            $table->string('academic_standing'); // good standing, probation, suspension, dismissed
            $table->boolean('deans_list')->default(false);
            $table->boolean('honors')->default(false);
            $table->text('notes')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->unique(['user_id', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_records');
    }
};
