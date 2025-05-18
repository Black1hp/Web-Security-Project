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
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('department_id')->after('professor_id')->constrained('departments');
            $table->string('semester')->after('department_id');
            $table->integer('capacity')->after('semester')->default(30);
            $table->integer('enrolled_count')->after('capacity')->default(0);
            $table->boolean('is_active')->after('enrolled_count')->default(true);
            $table->dateTime('registration_start')->after('is_active')->nullable();
            $table->dateTime('registration_end')->after('registration_start')->nullable();
            $table->decimal('tuition_per_credit', 10, 2)->after('registration_end')->default(0);
            $table->text('syllabus')->after('tuition_per_credit')->nullable();
            $table->string('location')->after('syllabus')->nullable();
            $table->string('meeting_days')->after('location')->nullable();
            $table->time('start_time')->after('meeting_days')->nullable();
            $table->time('end_time')->after('start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn([
                'department_id',
                'semester',
                'capacity',
                'enrolled_count',
                'is_active',
                'registration_start',
                'registration_end',
                'tuition_per_credit',
                'syllabus',
                'location',
                'meeting_days',
                'start_time',
                'end_time'
            ]);
        });
    }
};
