<?php

namespace App\Providers;

use App\Http\View\Composers\MenuComposer; // 추가
use Illuminate\Support\Facades\View; // 추가
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 프론트엔드 레이아웃에 메뉴 데이터를 바인딩합니다.
        View::composer('layouts.app', MenuComposer::class);
    }
}
