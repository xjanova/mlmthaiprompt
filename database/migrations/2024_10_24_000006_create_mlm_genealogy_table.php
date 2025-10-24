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
        // ลำดับเครือญาติ/สายงาน (Genealogy Tree)
        Schema::create('mlm_genealogy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ancestor_id')->constrained('users')->onDelete('cascade'); // บรรพบุรุษในสาย
            $table->integer('depth')->default(0); // ระยะห่าง (0 = ตัวเอง, 1 = ลูกโดยตรง)
            $table->string('path')->nullable(); // เส้นทาง เช่น "1/5/10/15"
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'depth']);
            $table->index('ancestor_id');
            $table->unique(['user_id', 'ancestor_id']);
        });

        // ประวัติการทำงานของ Network
        Schema::create('mlm_network_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('activity_type', [
                'joined',               // เข้าร่วม
                'referral',             // แนะนำคน
                'sales',                // ทำยอดขาย
                'rank_up',              // เลื่อนตำแหน่ง
                'rank_down',            // ลดตำแหน่ง
                'commission_earned',    // ได้ค่าคอมมิชชั่น
                'bonus_earned',         // ได้โบนัส
                'payout_requested',     // ขอถอนเงิน
                'payout_completed',     // ถอนเงินสำเร็จ
                'status_change'         // เปลี่ยนสถานะ
            ])->default('joined');
            $table->string('title'); // หัวข้อ
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2)->nullable(); // จำนวนเงิน (ถ้ามี)
            $table->json('metadata')->nullable(); // ข้อมูลเพิ่มเติม
            $table->foreignId('related_user_id')->nullable()->constrained('users')->onDelete('set null'); // ผู้ที่เกี่ยวข้อง
            $table->morphs('activityable'); // สำหรับ polymorphic relation
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'activity_type']);
            $table->index('created_at');
        });

        // สถิติ MLM
        Schema::create('mlm_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('stat_date'); // วันที่สถิติ
            $table->integer('total_referrals')->default(0); // จำนวนคนแนะนำทั้งหมด
            $table->integer('active_referrals')->default(0); // จำนวนคนแนะนำที่ active
            $table->integer('total_downlines')->default(0); // จำนวน downline ทั้งหมด
            $table->decimal('personal_sales', 15, 2)->default(0); // ยอดขายส่วนตัว
            $table->decimal('team_sales', 15, 2)->default(0); // ยอดขายทีม
            $table->decimal('left_leg_sales', 15, 2)->default(0); // ยอดขายขาซ้าย
            $table->decimal('right_leg_sales', 15, 2)->default(0); // ยอดขายขาขวา
            $table->decimal('commissions_earned', 15, 2)->default(0); // ค่าคอมมิชชั่นที่ได้
            $table->decimal('bonuses_earned', 15, 2)->default(0); // โบนัสที่ได้
            $table->decimal('total_earnings', 15, 2)->default(0); // รายได้รวม
            $table->decimal('total_payouts', 15, 2)->default(0); // การจ่ายเงินรวม
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'stat_date']);
            $table->unique(['user_id', 'stat_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_statistics');
        Schema::dropIfExists('mlm_network_activities');
        Schema::dropIfExists('mlm_genealogy');
    }
};
