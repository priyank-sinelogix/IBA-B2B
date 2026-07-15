<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageWebController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::with('sender')
            ->where('company_id', $request->user()->company_id)
            ->latest()->paginate(20);

        return view('messages.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        Message::create([
            'company_id' => $request->user()->company_id,
            'sender_id' => $request->user()->id,
            'body' => $request->input('body'),
        ]);

        return back()->with('success', 'Message sent.');
    }
}
