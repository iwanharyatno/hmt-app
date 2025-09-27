<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
        public function dashboard()
    {
        // nanti bisa ambil data dari database
        $totalUsers = 120; // contoh dummy data
        $totalLearning = 87;
        $totalHmt = 65;

        return view('admin.dashboard', compact('totalUsers', 'totalLearning', 'totalHmt'));
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
