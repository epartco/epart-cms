<?php

namespace App\Http\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // 메뉴 데이터를 가져옵니다. 우선 간단하게 모든 메뉴를 가져옵니다.
        // 추후 계층 구조나 활성화 상태 등을 고려하여 쿼리를 수정할 수 있습니다.
        $menus = Menu::orderBy('order', 'asc')->get(); // 'order' 컬럼 기준으로 정렬 (마이그레이션에 order 컬럼 추가 필요)

        $view->with('menus', $menus);
    }
}
