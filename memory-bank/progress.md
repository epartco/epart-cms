# 진행 상황: epart cms (초기 설정)

## 현재 상태

*   라라벨 프로젝트 기본 설정 완료.
*   사용자 인증 시스템 (Breeze) 구현 완료.
*   관리자 기본 레이아웃 및 대시보드 구현 완료.
*   역할/권한 관리 시스템 (Spatie Permission) 설정 완료.
*   사용자 관리 기능 (CRUD, 역할 할당) 구현 완료.
*   페이지 관리 기능 (CRUD) 구현 완료.
*   관리자 댓글 관리 기능 (목록 조회, 상태 변경, 삭제) 구현 완료.
*   게시판 관리 기능 (CRUD) 구현 완료.
*   게시글 관리 기능 (CRUD) 구현 완료.
*   포스트 관리 기능 (CRUD) 구현 완료.
*   프론트엔드 페이지, 포스트, 게시판 목록/상세 보기 기본 기능 구현 완료.
*   프론트엔드 게시글 상세 보기 및 댓글 표시/작성 기능 구현 완료.
*   관리자 메뉴 관리 기능 기본 CRUD 구현 완료 (컨트롤러, 라우트, 뷰, 레이아웃 링크).
*   **관리자 메뉴 항목 관리 기본 CRUD 구현 완료 (모델, 마이그레이션, 컨트롤러, 라우트, 뷰 업데이트 및 Alpine.js 통합).**
*   **관리자 메뉴 항목 중첩 UI 기본 구현 완료 (부모 선택 기능):**
    *   `Admin/MenuController` 수정 (`edit` 메소드에서 `$menuItems` 전달).
    *   `admin/menus/edit.blade.php` 수정 (부모 선택 드롭다운 추가, 부모 정보 표시, Alpine.js 초기화 수정).
*   프론트엔드 네비게이션 동적 생성 완료 (View Composer, Service Provider, 레이아웃 수정).
*   **미디어 라이브러리 기본 기능 구현 완료:**
    *   `spatie/laravel-medialibrary` 패키지 설치 및 설정 (마이그레이션 실행).
    *   관리자 레이아웃에 미디어 라이브러리 링크 추가.
    *   `Admin/MediaController` 생성 및 라우트 정의.
    *   미디어 라이브러리 뷰 (`index.blade.php`) 생성 (업로드 폼, 목록).
    *   `Admin/MediaController` 핵심 메소드 (`index`, `store`, `destroy`) 구현.
    *   `User` 모델에 `InteractsWithMedia` 트레이트 및 `thumbnail` 변환 추가.
*   **미디어 라이브러리 편집 기능 구현 완료:** 미디어 이름 및 alt 텍스트 수정 가능.
*   **사이트 전반 설정 기능 구현 완료:** 관리자 페이지에서 사이트 이름, 기본 SEO 정보, 추적 코드 등 설정 가능.

## 완료된 작업

*   **미디어 라이브러리 편집 기능 구현 완료:**
    *   `app/Http/Controllers/Admin/MediaController.php` 수정: `edit` 및 `update` 메소드 추가.
    *   `resources/views/admin/media/index.blade.php` 수정: '수정' 버튼 추가.
    *   `resources/views/admin/media/edit.blade.php` 뷰 생성: 편집 폼 구현.
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
*   **관리자 메뉴 항목 중첩 UI 기본 구현 (부모 선택 기능):** (상세 내용은 위 '현재 상태' 참조)
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
*   **미디어 라이브러리 기본 기능 구현:** (상세 내용은 위 '현재 상태' 참조)
*   **관리자 메뉴 항목 관리 기본 CRUD 구현:**
    *   `Admin/MenuItemController` 생성 및 `store`, `update`, `destroy`, `updateOrder` 메소드 구현 (AJAX 처리)
    *   `routes/web.php`에 `MenuItem` 관련 중첩 리소스 및 순서 변경 라우트 추가
    *   `admin/menus/edit.blade.php`에 Alpine.js 컴포넌트 추가하여 메뉴 항목 목록 표시, 추가, 수정, 삭제 기능 구현 (AJAX 연동)
    *   `MenuItem` 모델 생성 및 `$fillable`, 관계 (`menu`, `parent`, `children`) 정의
    *   `Menu` 모델 `$fillable` 수정 및 `items`, `allItems` 관계 정의
    *   `menu_items` 테이블 마이그레이션 생성 및 실행 (스키마 정의 포함)
*   프론트엔드 네비게이션 동적 생성 완료:
    *   `MenuComposer` 생성 (`app/Http/View/Composers/MenuComposer.php`)
    *   `AppServiceProvider`에 `MenuComposer` 등록
    *   `layouts/app.blade.php` 수정하여 동적 메뉴 및 사용자 인증 상태 표시
*   관리자 메뉴 관리 기능 기본 CRUD 구현 (`Admin/MenuController`, 라우트, `index`/`create`/`edit` 뷰, 레이아웃 링크 추가)
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
*   관리자 댓글 관리 기능 구현 (`CommentController` 및 관련 뷰/로직)
*   관리자 레이아웃 사이드바에 게시판/게시글 링크 추가
*   게시글 CRUD 기능 구현 (`ArticleController` 및 관련 뷰)
*   게시판 CRUD 기능 구현 (`BoardController` 및 관련 뷰)
*   `Board`, `Article`, `Comment` 모델 및 마이그레이션 생성/실행 및 관계 정의
*   `Admin/BoardController`, `Admin/ArticleController` 생성 및 리소스 라우트 정의
*   관리자 레이아웃 사이드바에 포스트 링크 추가
*   포스트 수정/삭제 기능 구현 (`edit`, `update`, `destroy` 메소드 및 관련 뷰/로직)
*   포스트 목록/생성 기능 구현 (`index`, `create`, `store` 메소드 및 관련 뷰)
*   `Post`, `Category`, `Tag` 모델 및 마이그레이션 생성/실행 (`post_tag` 피벗 테이블 포함)
*   `Admin/PostController` 생성 및 리소스 라우트 정의
*   페이지 수정/삭제 기능 구현 (`edit`, `update`, `destroy` 메소드 및 `edit.blade.php` 뷰)
*   사용자 관리 기능 전체 구현 (CRUD, 역할 할당, 플래시 메시지)
*   `Page` 모델 및 마이그레이션 생성/실행
*   `Admin/PageController` 생성 및 리소스 라우트 정의
*   페이지 목록 페이지 (`admin/pages/index`) 구현 (컨트롤러 + 뷰)
*   페이지 생성 페이지 (`admin/pages/create`) 구현 (컨트롤러 + 뷰)
*   페이지 저장 로직 (`PageController@store`) 구현
*   관리자 레이아웃 사이드바에 페이지 링크 추가
*   초기 관리자 계정 시딩
*   Spatie Permission 설정 및 역할 시딩
*   Breeze 인증 설정 및 프론트엔드 빌드
*   관리자 레이아웃 및 대시보드 생성
*   라라벨 프로젝트 생성 및 `.env` 설정
*   메모리 뱅크 초기 설정 (모든 핵심 파일 생성)
*   데이터베이스 마이그레이션 (기본 + Spatie Permission)
*   Laravel Breeze 설치 및 프론트엔드 빌드
*   관리자 레이아웃 (`layouts.admin`) 생성
*   관리자 대시보드 라우트 및 뷰 생성
*   Spatie Permission 패키지 설치, 설정 게시, 마이그레이션
*   `User` 모델에 `HasRoles` 트레이트 추가
*   `RoleSeeder` 생성 및 실행 ('Admin', 'Editor' 역할 생성)
*   `Admin/UserController` 생성 및 리소스 라우트 정의
*   사용자 목록 페이지 (`admin/users/index`) 구현 (컨트롤러 + 뷰)
*   사용자 생성 페이지 (`admin/users/create`) 구현 (컨트롤러 + 뷰)

## 남은 작업 (단기)

*   (다음 작업 정의 필요)

## 알려진 이슈 또는 고려 사항

*   현재 없음.

## 주요 결정 변경 사항 기록

*   (초기 단계이므로 해당 없음)
