# 활성 컨텍스트: epart cms (관리자 메뉴 항목 순서 변경 완료)

## 현재 작업 초점

*   **Post 슬러그 기능 구현 완료.**
*   다음 주요 기능 구현 시작 (미디어 라이브러리 또는 사이트 설정)

## 최근 변경 사항

*   **Post 슬러그 기능 개선:**
    *   `app/Models/Post.php` 수정: `sluggable()` 메소드를 `Page` 모델과 동일하게 수정하여 한글 처리 및 `onUpdate` 옵션 적용. (`Sluggable` 트레이트 및 기본 설정은 이전 단계에서 완료됨)
    *   `database/migrations/2025_04_08_135212_create_posts_table.php` 확인: `slug` 컬럼 존재 확인.
    *   불필요한 `add_slug_to_posts_table` 마이그레이션 삭제.
    *   `app/Http/Controllers/PostController.php`의 `show` 메소드 수정: 암시적 라우트 모델 바인딩 사용.
    *   `routes/web.php` 및 `resources/views/posts/index.blade.php` 확인: 이미 슬러그 사용 중임을 확인.
*   **관리자 메뉴 항목 순서 변경 기능 구현 완료:**
    *   `resources/views/admin/menus/edit.blade.php` 뷰 수정:
        *   SortableJS 라이브러리 임포트 및 적용 (`x-ref`, `handle` 클래스 추가)
        *   Alpine.js 컴포넌트 (`menuItemsManager`)에 `initSortable` 및 `updateOrder` 메소드 추가 (AJAX 요청 로직 포함)
    *   `app/Http/Controllers/Admin/MenuItemController.php`의 `updateOrder` 메소드 수정 (프론트엔드 요청 데이터 형식(`orderedIds`)에 맞게 파라미터 수정)
    *   `routes/web.php`에 순서 업데이트 라우트 (`admin.menus.items.updateOrder`) 존재 확인
*   **관리자 메뉴 항목 관리 기본 CRUD 구현 완료:**
    *   `Admin/MenuItemController` 생성 및 `store`, `update`, `destroy`, `updateOrder` 메소드 구현 (AJAX 처리)
    *   `routes/web.php`에 `MenuItem` 관련 중첩 리소스 및 순서 변경 라우트 추가
    *   `MenuItem` 모델 및 마이그레이션 생성 및 실행 (`menu_items` 테이블 스키마 정의)
    *   `Menu` 및 `MenuItem` 모델 관계 정의 및 수정
    *   `admin/menus/edit.blade.php`에 Alpine.js 컴포넌트 추가하여 메뉴 항목 목록 표시, 추가, 수정, 삭제 기능 구현 (AJAX 연동)
*   프론트엔드 네비게이션 동적 생성 완료:
    *   `MenuComposer` 생성하여 메뉴 데이터 로드
    *   `AppServiceProvider`에 `MenuComposer` 등록
    *   `layouts/app.blade.php` 수정하여 동적 메뉴 및 사용자 인증 상태 표시
*   관리자 메뉴 관리 기능 기본 CRUD 구현 완료 (`Admin/MenuController`, 라우트, `index`/`create`/`edit` 뷰, 레이아웃 링크 추가)
*   `menus` 테이블 마이그레이션 실행 완료
*   `Menu` 모델 업데이트 (`$fillable`, 관계 정의)
*   `Menu` 모델 및 마이그레이션 생성
*   프론트엔드 댓글 작성 폼 구현 (`articles/show.blade.php` 업데이트, 오류/성공 메시지 처리)
*   프론트엔드 댓글 저장 컨트롤러 생성 및 구현 (`app/Http/Controllers/CommentController.php`, `store` 메소드)
*   프론트엔드 댓글 저장 라우트 정의 (`routes/web.php`)
*   프론트엔드 게시글 상세 보기 뷰 생성 및 구현 (`articles/show.blade.php`, 댓글 목록 표시 포함)
*   프론트엔드 게시글 상세 보기 컨트롤러 메소드 구현 (`ArticleController@show`, 댓글 로딩 포함)
*   프론트엔드 게시글 상세 보기 라우트 정의 (`routes/web.php`)
*   프론트엔드 게시판 목록/상세(게시글 목록) 뷰 생성 (`boards/index.blade.php`, `boards/show.blade.php`)
*   프론트엔드 게시판 컨트롤러 (`BoardController`) 생성 및 `index`, `show` 메소드 구현
*   프론트엔드 게시판 목록/상세 라우트 정의 (`routes/web.php`)
*   프론트엔드 포스트 목록/상세 뷰 생성 (`posts/index.blade.php`, `posts/show.blade.php`)
*   프론트엔드 포스트 컨트롤러 (`PostController`) 생성 및 `index`, `show` 메소드 구현
*   프론트엔드 포스트 목록/상세 라우트 정의 (`routes/web.php`)
*   프론트엔드 페이지 뷰 생성 (`pages/show.blade.php`)
*   프론트엔드 페이지 컨트롤러 (`PageController`) 생성 및 `show` 메소드 구현
*   프론트엔드 페이지 표시 라우트 정의 (`routes/web.php`)
*   프론트엔드 기본 레이아웃 수정 (`layouts/app.blade.php`: 네비게이션/헤더 주석 처리, 푸터 추가)
*   관리자 레이아웃 사이드바에 댓글 링크 추가
*   관리자 댓글 관리 기능 구현 (`CommentController` 및 관련 뷰/로직: 목록, 상태 변경, 삭제)
*   관리자 레이아웃 사이드바에 게시판/게시글 링크 추가
*   게시글 CRUD 기능 구현 (`ArticleController` 및 관련 뷰)
*   게시판 CRUD 기능 구현 (`BoardController` 및 관련 뷰)
*   `Board`, `Article`, `Comment` 모델 및 마이그레이션 생성/실행 및 관계 정의
*   `Admin/BoardController`, `Admin/ArticleController`, `Admin/CommentController` 생성 및 관련 라우트 정의
*   포스트 관리 기능 전체 구현 (CRUD)
*   페이지 관리 기능 전체 구현 (CRUD)
*   사용자 관리 기능 전체 구현 (CRUD, 역할 할당, 플래시 메시지)
*   관리자 레이아웃 사이드바에 페이지/포스트 링크 추가
*   초기 관리자 계정 및 역할 시딩 완료
*   Spatie Permission 패키지 설정 및 마이그레이션 완료
*   Laravel Breeze (Blade 스택) 설치 및 프론트엔드 빌드 완료
*   관리자 레이아웃 (`layouts.admin`) 및 대시보드 생성 완료

## 다음 단계 (예정)

1.  **미디어 라이브러리 기능 구현:**
    *   `spatie/laravel-medialibrary` 패키지 설치 및 설정
    *   이미지/파일 업로드 및 관리 UI 구현
2.  **사이트 전반 설정 기능 구현:**
    *   설정 모델 또는 시스템 구현
    *   관리자 UI에서 사이트 정보, 기본 SEO, 추적 코드 등 설정 관리

## 주요 결정 및 고려 사항

*   프로젝트명: epart cms
*   목적: 기업용 웹사이트 CMS (페이지, 포스트, 게시판 관리), SEO 중점
*   기술 스택: Laravel (최신 LTS), PHP, MySQL, Blade, Tailwind CSS, Alpine.js (Breeze 기본)
*   데이터베이스: MySQL 사용 확정
*   관리자 프론트엔드: 기본 Blade 템플릿 + Tailwind CSS 사용 확정
*   인증: Laravel Breeze 사용
*   권한 관리: `spatie/laravel-permission` 사용

## 학습 및 통찰

*   메모리 뱅크 시스템은 프로젝트의 목표, 요구사항, 기술적 결정 등을 명확하게 문서화하여 일관성 있는 개발을 지원하는 데 필수적입니다.
*   `progress.md`와 실제 코드 상태 간의 불일치를 발견하고 수정하여 문서의 정확성을 유지하는 것이 중요합니다.
