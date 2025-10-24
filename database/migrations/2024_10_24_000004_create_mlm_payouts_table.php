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
        Schema::create('mlm_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('payout_reference')->unique(); // เลขที่การจ่ายเงิน
            $table->decimal('amount', 15, 2)->default(0); // จำนวนเงิน
            $table->decimal('fee', 15, 2)->default(0); // ค่าธรรมเนียม
            $table->decimal('net_amount', 15, 2)->default(0); // จำนวนสุทธิ
            $table->enum('payment_method', ['bank_transfer', 'paypal', 'stripe', 'cash', 'crypto'])->default('bank_transfer');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->text('payment_details')->nullable(); // รายละเอียดการจ่ายเงิน (JSON)
            $table->string('transaction_id')->nullable(); // รหัสธุรกรรม
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null'); // ผู้ดำเนินการ
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index('payout_reference');
            $table->index('requested_at');
        });

        // รายการ Commission ที่รวมอยู่ใน Payout
        Schema::create('mlm_payout_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payout_id')->constrained('mlm_payouts')->onDelete('cascade');
            $table->foreignId('commission_id')->constrained('mlm_commissions')->onDelete('cascade');
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();

            // Indexes
            $table->index('payout_id');
            $table->index('commission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_payout_commissions');
        Schema::dropIfExists('mlm_payouts');
    }
};
