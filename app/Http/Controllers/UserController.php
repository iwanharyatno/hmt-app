<?php

namespace App\Http\Controllers;

use App\Models\HmtHistory;
use App\Models\HmtQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function dashboard()
    {
        $latestLearningStyle = null;
        if (Auth::user()) {
            $user = Auth::user()->load(['learningStyleResults' => fn($q) => $q->latest()->limit(1)]);
            $latestLearningStyle = $user->learningStyleResults->first();
        }

        return view('user.dashboard', compact('latestLearningStyle'));
    }
    public function contact()
    {
        return view('user.contact');
    }

    // Quiz: Learning Style
    public function learningStyle()
    {
        return view('user.quiz.learning-style');
    }

    // Result
    public function result()
    {
        return view('user.result');
    }
}
