# 기술 컨텍스트: epart cms

## 백엔드

*   **프레임워크:** Laravel (최신 LTS 버전 사용 예정)
*   **언어:** PHP (Laravel 요구 버전에 맞춤)
*   **데이터베이스:** MySQL (사용자 동의)
*   **웹 서버:** Nginx 또는 Apache (개발 환경에서는 Laravel Sail 또는 로컬 서버 사용 가능)
*   **패키지 관리자:** Composer

## 프론트엔드 (관리자 페이지)

*   **템플릿 엔진:** Laravel Blade (사용자 동의)
*   **CSS:** 기본적인 CSS 또는 Tailwind CSS (Laravel Breeze/Jetstream 설치 시 결정)
*   **JavaScript:** Alpine.js (Laravel Breeze 선택 시) 또는 기본 JavaScript. 필요에 따라 Livewire 고려 가능.

## 프론트엔드 (생성될 웹사이트)

*   **템플릿 엔진:** Laravel Blade
*   **구조:** 테마 시스템 구현 가능성을 염두에 두고 설계 (초기에는 기본 테마 제공)

## 주요 라라벨 패키지 (예상/고려)

*   **인증:** Laravel Breeze 또는 Laravel Jetstream (Breeze를 기본으로 고려)
*   **권한 관리:** `spatie/laravel-permission`
*   **미디어 관리:** `spatie/laravel-medialibrary`
*   **SEO 관리:** `spatie/laravel-seo` 또는 직접 구현
*   **슬러그 생성:** `cviebrock/eloquent-sluggable`
*   **WYSIWYG 에디터:** TinyMCE, CKEditor 등 (추후 선정)

## 개발 환경

*   로컬 개발 환경: Laravel Sail (Docker 기반) 또는 Laragon, Valet 등 권장
*   버전 관리: Git

## 제약 조건 및 고려 사항

*   PHP 및 Laravel 버전에 대한 서버 호환성 확인 필요
*   데이터베이스 스키마 설계 시 확장성 고려
*   SEO 기능 구현 시 최신 SEO 모범 사례 준수
