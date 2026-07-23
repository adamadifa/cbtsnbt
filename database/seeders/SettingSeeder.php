<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_title', 'value' => 'Lulus SNBT', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_logo', 'value' => null, 'type' => 'image', 'group' => 'branding'],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'image', 'group' => 'branding'],
            ['key' => 'admin_email', 'value' => 'admin@cbt.test', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_whatsapp', 'value' => '6281234567890', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'certificate_signature_name', 'value' => 'Lulus SNBT Admin', 'type' => 'text', 'group' => 'certificate'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
