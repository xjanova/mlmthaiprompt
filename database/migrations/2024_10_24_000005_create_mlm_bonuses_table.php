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
        Schema::create('mlm_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'rank_achievement',     // โบนัสจากการได้ตำแหน่ง
                'sales_milestone',      // โบนัสจากยอดขายถึงเป้า
                'recruitment',          // โบนัสจากการสรรหา
                'team_building',        // โบนัสจากการสร้างทีม
                'leadership',           // โบนัสผู้นำ
                'matching',             // โบนัส Matching
                'monthly',              // โบนัสรายเดือน
                'quarterly',            // โบนัสรายไตรมาส
                'yearly',               // โบนัสรายปี
                'special'               // โบนัสพิเศษ
            ])->default('special');
            $table->string('title'); // ชื่อโบนัส
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('qualified_amount', 15, 2)->default(0); // จำนวนที่ต้องผ่านเกณฑ์
            $table->enum('status', ['pending', 'approved', 'paid', 'expired', 'cancelled'])->default('pending');
            $table->foreignId('rank_id')->nullable()->constrained('mlm_ranks')->onDelete('set null');
            $table->text('conditions')->nullable(); // เงื่อนไขการได้รับ (JSON)
            $table->date('period_start')->nullable(); // งวดเริ่มต้น
            $table->date('period_end')->nullable(); // งวดสิ้นสุด
            $table->timestamp('earned_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // วันหมดอายุ
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'type', 'status']);
            $table->index('earned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_bonuses');
    }
};
