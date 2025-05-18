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
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('transaction_type'); // tuition, fee, payment, refund, scholarship
            $table->string('reference_type')->nullable(); // course, semester, etc.
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of the referenced entity
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable(); // credit card, bank transfer, cash
            $table->string('transaction_id')->nullable(); // external payment reference
            $table->string('status'); // pending, completed, failed, refunded
            $table->text('description')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};
