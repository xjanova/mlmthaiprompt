<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

Artisan::command('pc', function () {

    $paths = [
        'storage/',
        'bootstrap/cache/',
    ];

    // ใช้ Laravel's File facade แทน exec() เพื่อความปลอดภัย
    foreach ($paths as $path) {
        $fullPath = base_path($path);

        if (File::exists($fullPath)) {
            try {
                // ตั้งค่า permissions ที่ปลอดภัยกว่า (775 แทน 777)
                // ใช้ระบบ permissions ของ PHP แทน exec
                if (File::isDirectory($fullPath)) {
                    // สำหรับ directories ใช้ 755 (owner: rwx, group: r-x, others: r-x)
                    chmod($fullPath, 0755);
                    $this->info("Permissions changed successfully for $path");
                } else {
                    // สำหรับ files ใช้ 644 (owner: rw-, group: r--, others: r--)
                    chmod($fullPath, 0644);
                    $this->info("Permissions changed successfully for $path");
                }
            } catch (\Exception $e) {
                $this->error("Failed to change permissions for $path: " . $e->getMessage());
            }
        } else {
            $this->warn("Path does not exist: $path");
        }
    }

    // Clear various caches
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');

    $this->info('All caches cleared and permissions set!');
})->describe('Clear all types of caches and set file permissions');


