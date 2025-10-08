<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalLearningStyle = \App\Models\LearningStyleResult::distinct('user_id')->count('user_id');
        $totalHmtParticipants = \App\Models\HmtHistory::distinct('user_id')->count('user_id');

        return view('admin.dashboard', compact('totalLearningStyle', 'totalHmtParticipants'));
    }

    /**
     * Manajemen Kuis Learning Style
     */
    public function learningIndex()
    {
        // ambil data kuis learning style dari database (sementara dummy)


        return view('admin.learning-style.index');
    }
    public function learningCreate()
    {
        // ambil data kuis learning style dari database (sementara dummy)


        return view('admin.learning-style.create');
    }

    /**
     * Manajemen Kuis HMT
     */
    public function hmtIndex()
    {


        return view('admin.hmt.index');
    }
    public function hmtCreate()
    {


        return view('admin.hmt.create');
    }
}
