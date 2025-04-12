<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Laravel CMS');
        $this->migrator->add('general.site_description', '라라벨로 구축된 강력한 CMS 시스템');
        $this->migrator->add('general.default_meta_title', 'Laravel CMS');
        $this->migrator->add('general.default_meta_description', '라라벨로 구축된 강력한 CMS 시스템');
        $this->migrator->add('general.robots_txt', null);
        $this->migrator->add('general.google_analytics_id', null);
        $this->migrator->add('general.google_site_verification', null);
    }
};
