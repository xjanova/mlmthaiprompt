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
        Schema::create('mlm_ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อตำแหน่ง เช่น Bronze, Silver, Gold
            $table->string('name_th')->nullable(); // ชื่อภาษาไทย
            $table->text('description')->nullable();
            $table->integer('level')->default(0); // ระดับตำแหน่ง
            $table->decimal('min_personal_sales', 15, 2)->default(0); // ยอดขายส่วนตัวขั้นต่ำ
            $table->decimal('min_group_sales', 15, 2)->default(0); // ยอดขายกลุ่มขั้นต่ำ
            $table->integer('min_direct_referrals')->default(0); // จำนวนคนแนะนำโดยตรงขั้นต่ำ
            $table->integer('min_qualified_legs')->default(0); // จำนวน leg ที่ผ่านเกณฑ์
            $table->decimal('commission_rate', 5, 2)->default(0); // % ค่าคอมมิชชั่น
            $table->decimal('monthly_bonus', 15, 2)->default(0); // โบนัสรายเดือน
            $table->string('badge_icon')->nullable(); // ไอคอนตรา
            $table->string('badge_color')->default('#cccccc'); // สีตรา
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('level');
            $table->index('sort_order');
        });

        // User Ranks (ตำแหน่งของผู้ใช้)
        Schema::create('mlm_user_ranks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('rank_id')->constrained('mlm_ranks')->onDelete('cascade');
            $table->timestamp('achieved_at')->nullable(); // วันที่ได้ตำแหน่ง
            $table->boolean('is_current')->default(true); // ตำแหน่งปัจจุบัน
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'is_current']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_user_ranks');
        Schema::dropIfExists('mlm_ranks');
    }
};
