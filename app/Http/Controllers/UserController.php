<?php

namespace App\Http\Controllers;

use App\Models\HmtHistory;
use App\Models\HmtQuestion;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function dashboard()
    {
        return view('user.dashboard');
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
