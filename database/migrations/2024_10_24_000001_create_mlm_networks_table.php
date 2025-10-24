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
        Schema::create('mlm_networks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sponsor_id')->nullable()->constrained('users')->onDelete('set null'); // ผู้แนะนำ
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null'); // parent ในโครงสร้าง
            $table->enum('position', ['left', 'right', 'center'])->nullable(); // สำหรับ Binary
            $table->enum('network_type', ['binary', 'unilevel', 'matrix'])->default('binary');
            $table->integer('level')->default(0); // ระดับในโครงสร้าง
            $table->integer('left_count')->default(0); // จำนวนคนฝั่งซ้าย
            $table->integer('right_count')->default(0); // จำนวนคนฝั่งขวา
            $table->decimal('left_sales', 15, 2)->default(0); // ยอดขายฝั่งซ้าย
            $table->decimal('right_sales', 15, 2)->default(0); // ยอดขายฝั่งขวา
            $table->decimal('personal_sales', 15, 2)->default(0); // ยอดขายส่วนตัว
            $table->decimal('group_sales', 15, 2)->default(0); // ยอดขายกลุ่ม
            $table->boolean('is_active')->default(true);
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'sponsor_id']);
            $table->index(['parent_id', 'position']);
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_networks');
    }
};
