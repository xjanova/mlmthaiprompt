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
        // ตั้งค่าค่าคอมมิชชั่น
        Schema::create('mlm_commission_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อการตั้งค่า
            $table->enum('type', ['direct', 'indirect', 'matching', 'leadership', 'binary'])->default('direct');
            $table->integer('level')->default(1); // ระดับที่ได้คอมมิชชั่น
            $table->decimal('percentage', 5, 2)->default(0); // % ค่าคอมมิชชั่น
            $table->decimal('flat_amount', 15, 2)->default(0); // จำนวนเงินคงที่
            $table->decimal('min_sales', 15, 2)->default(0); // ยอดขายขั้นต่ำ
            $table->decimal('max_commission', 15, 2)->nullable(); // ค่าคอมมิชชั่นสูงสุด
            $table->boolean('is_active')->default(true);
            $table->text('conditions')->nullable(); // เงื่อนไขเพิ่มเติม (JSON)
            $table->timestamps();
        });

        // บันทึกค่าคอมมิชชั่นที่ได้รับ
        Schema::create('mlm_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ผู้ได้รับ
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade'); // ผู้ทำยอดขาย
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null'); // Order ที่เกี่ยวข้อง
            $table->foreignId('commission_setting_id')->nullable()->constrained('mlm_commission_settings')->onDelete('set null');
            $table->enum('type', ['direct', 'indirect', 'matching', 'leadership', 'binary', 'bonus'])->default('direct');
            $table->integer('level')->default(1); // ระดับที่ได้คอมมิชชั่น
            $table->decimal('amount', 15, 2)->default(0); // จำนวนเงิน
            $table->decimal('base_amount', 15, 2)->default(0); // ยอดที่ใช้คำนวณ
            $table->decimal('percentage', 5, 2)->default(0); // % ที่ได้
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->text('description')->nullable();
            $table->timestamp('earned_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['from_user_id', 'type']);
            $table->index('earned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_commissions');
        Schema::dropIfExists('mlm_commission_settings');
    }
};
