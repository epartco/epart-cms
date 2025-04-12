<?php

namespace Database\Seeders;

use App\Settings\GeneralSettings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // GeneralSettings 인스턴스 생성 및 기본값 설정
        $settings = app(GeneralSettings::class);
        
        // 기본값이 이미 마이그레이션에 설정되어 있으므로 필요한 경우에만 추가 설정
        if (empty($settings->site_name)) {
            $settings->site_name = 'Laravel CMS';
        }
        
        if (empty($settings->site_description)) {
            $settings->site_description = '라라벨로 구축된 강력한 CMS 시스템';
        }
        
        // 설정 저장
        $settings->save();
    }
}
