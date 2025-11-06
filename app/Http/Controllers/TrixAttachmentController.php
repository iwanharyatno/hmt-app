<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrixAttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'attachment' => 'required|image|max:2048',
        ]);

        $path = $request->file('attachment')->store('attachments', 'public');
        $url = Storage::url($path);

        return response()->json(['url' => $url]);
    }
}
