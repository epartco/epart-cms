# 활성 컨텍스트: epart cms (사이트 설정 기능 구현 완료)

## 현재 작업 초점

*   **미디어 라이브러리 편집 기능 구현 완료.**
*   다음 작업: 관리자 메뉴 항목 중첩 UI 개선 진행.

## 최근 변경 사항

*   **미디어 라이브러리 편집 기능 구현 완료:**
    *   `app/Http/Controllers/Admin/MediaController.php` 수정: `edit` 및 `update` 메소드 추가 (Route Model Binding 사용, alt 텍스트 및 이름 업데이트 로직 구현).
    *   `routes/web.php` 확인: `Route::resource('media', ...)` 가 `edit`, `update` 라우트를 포함하므로 별도 추가 불필요.
    *   `resources/views/admin/media/index.blade.php` 수정: 각 미디어 항목에 '수정' 버튼 추가.
    *   `resources/views/admin/media/edit.blade.php` 뷰 생성: 미디어 미리보기, 이름 및 alt 텍스트 편집 폼 구현.
*   **사이트 전반 설정 기능 구현 완료:**
    *   `spatie/laravel-settings` 패키지 설치 및 설정 (`settings` 테이블 마이그레이션 확인).
    *   `app/Settings/GeneralSettings.php` 설정 클래스 생성 (site_name, site_description, default_meta_title, default_meta_description, robots_txt, google_analytics_id, google_site_verification 속성 정의).
    *   `app/Http/Controllers/Admin/SettingController.php` 생성 및 `edit`, `update` 메소드 구현 (설정 로드 및 저장, 유효성 검사 포함).
    *   `routes/web.php`에 관리자 설정 라우트 (`/admin/settings` GET, PUT) 추가.
    *   `resources/views/layouts/admin.blade.php` 사이드바에 'Settings' 링크 추가 및 활성화 로직 적용.
    *   `resources/views/admin/settings/edit.blade.php` 뷰 생성 (설정 폼 구현, 오류/성공 메시지 표시).
    *   `resources/views/layouts/admin.blade.php` 수정: `<title>` 및 사이드바 헤더에 `site_name` 설정 값 적용.
    *   `resources/views/layouts/app.blade.php` 수정: `<title>`, 기본 `<meta name="description">`, 로고 텍스트, 푸터 저작권에 설정 값 적용.
    *   `app/Http/Controllers/RobotsController.php` 생성 및 `index` 메소드 구현 (`robots.txt` 내용 반환).
    *   `routes/web.php`에 `/robots.txt` 라우트 추가.
*   **관리자 메뉴 항목 중첩 UI 기본 구현:**
    *   `app/Http/Controllers/Admin/MenuController.php` 수정: `edit` 메소드에서 모든 메뉴 항목 목록(`$menuItems`)을 뷰로 전달하도록 변경.
    *   `resources/views/admin/menus/edit.blade.php` 수정:
        *   항목 추가/수정 폼에 부모 항목 선택 `<select>` 드롭다운 추가 (Alpine.js 연동).
        *   메뉴 항목 목록에 부모 항목 정보 표시 추가.
        *   Alpine.js 컴포넌트 초기화 시 `$menuItems` 변수 사용하도록 수정.
*   **미디어 라이브러리 확장 (에디터 연동):**
    *   `tinymce` npm 패키지 설치.
    *   `resources/js/app.js` 수정: TinyMCE 임포트 및 초기화 로직 추가 (`.tinymce-editor` 셀렉터).
    *   페이지 및 포스트 생성/수정 폼 (`create.blade.php`, `edit.blade.php`)의 content `<textarea>`에 `tinymce-editor` 클래스 추가.
    *   `resources/views/components/admin/media-library-modal.blade.php` 컴포넌트 생성 (Alpine.js 기반).
    *   `routes/web.php` 수정: 미디어 목록 JSON 반환 라우트 (`admin.media.listJson`) 추가.
    *   `app/Http/Controllers/Admin/MediaController.php` 수정: `listJson` 메소드 구현 (미디어 정보 포맷팅 및 JSON 반환).
    *   미디어 라이브러리 모달 컴포넌트 수정: `loadMedia` 함수에서 `fetch`를 사용하여 `admin.media.listJson` 라우트 호출하도록 변경.
    *   `resources/views/layouts/admin.blade.php` 수정: 미디어 라이브러리 모달 컴포넌트 포함.
    *   `resources/js/app.js` 수정: TinyMCE 초기화 옵션에 `file_picker_callback` 추가하여 이미지/미디어 버튼 클릭 시 모달 열도록 구현.
    *   `npm run build` 실행하여 프론트엔드 에셋 빌드.
*   **미디어 라이브러리 기본 기능 구현 완료:**
    *   `spatie/laravel-medialibrary` 패키지 설치 및 마이그레이션 실행 (`media` 테이블 생성).
    *   `resources/views/layouts/admin.blade.php` 수정: 사이드바에 'Media Library' 링크 추가.
    *   `Admin/MediaController` 생성 및 리소스 라우트 정의 (`routes/web.php`).
    *   `resources/views/admin/media/index.blade.php` 뷰 생성: 파일 업로드 폼 및 미디어 목록 표시 구조 구현.
    *   `Admin/MediaController` 구현: `index` (목록 조회, 페이지네이션), `store` (파일 업로드, 유효성 검사, User 모델 연동), `destroy` (파일 삭제) 메소드 구현.
    *   `app/Models/User.php` 수정: `HasMedia` 인터페이스 구현 및 `InteractsWithMedia` 트레이트 추가.
    *   `app/Models/User.php` 수정: `registerMediaConversions` 메소드 추가하여 'thumbnail' 변환 정의.
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

*   (다음 작업 정의 필요) - 예: SEO 기능 강화, 테마 시스템 구현, 대시보드 위젯 추가 등

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
