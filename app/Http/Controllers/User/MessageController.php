<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Message;

class MessageController extends Controller
{
    public function index($chatId)
    {
        $messages = Message::where('chat_id', $chatId)->with('sender')->get();
        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $message = Message::create([
            'chat_id' => $request->input('chat_id'),
            'sender_id' => $request->user()->id,
            'message' => $request->input('message')
        ]);

        return response()->json($message, 201);
    }
}