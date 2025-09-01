<?php

if (!function_exists('module_is_active')) {
    /**
     * ตรวจว่า module ชื่อ $name เปิดใช้อยู่หรือไม่
     * คุณปรับ logic ด้านในให้เข้ากับระบบจริงของคุณได้
     */
    function module_is_active(string $name): bool
    {
        // ตัวอย่าง: อ่านค่าจาก config/modules.php หรือ .env
        // return (bool) (config("modules.{$name}.enabled") ?? false);

        // ถ้ายังไม่มี config ใช้ default = true/false ตามต้องการ
        return (bool) (config("modules.{$name}.enabled") ?? false);
    }
}
