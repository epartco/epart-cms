<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $baseRules = [
            'article_id' => 'required|exists:articles,id',
            'content' => 'required|string|max:1000',
        ];

        $guestRules = [];
        if (!Auth::check()) { // If user is a guest
            $guestRules = [
                'guest_name' => 'required|string|max:255',
                'guest_password' => 'required|string|min:4',
            ];
        }

        $rules = array_merge($baseRules, $guestRules);
        $validatedData = $request->validate($rules);

        $article = Article::findOrFail($validatedData['article_id']);

        $comment = new Comment();
        $comment->article_id = $article->id;
        $comment->content = $validatedData['content'];
        $comment->status = 'pending'; // Default status, requires admin approval

        if (Auth::check()) {
            $comment->user_id = Auth::id();
        } else {
            $comment->guest_name = $validatedData['guest_name'];
            // Ensure guest_password exists before hashing
            if (!empty($validatedData['guest_password'])) {
                $comment->guest_password = Hash::make($validatedData['guest_password']);
            } else {
                // Handle case where guest password might be missing despite validation (should ideally not happen)
                return back()->withErrors(['guest_password' => '비회원 댓글 작성 시 비밀번호는 필수입니다.'])->withInput();
            }
        }

        $comment->save();

        return redirect()->route('articles.show', $article)->with('success', '댓글이 성공적으로 등록되었습니다. 관리자 승인 후 표시됩니다.');
    }
}
