<?php

namespace App\Http\View\Composers;

use App\Models\Menu;
use App\Models\MenuItem; // MenuItem 모델 추가
use Illuminate\View\View;
use Illuminate\Support\Collection; // Collection 추가

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
        // 모든 메뉴 항목을 가져옵니다 (최상위 항목만)
        $menuItems = MenuItem::whereNull('parent_id')
                             ->with('children') // 자식 항목들을 Eager Load
                             ->orderBy('order', 'asc')
                             ->get();

        // 디버깅 코드 제거

        // '$mainMenuItems' 라는 이름으로 뷰에 전달합니다.
        $view->with('mainMenuItems', $menuItems);
    }
}
