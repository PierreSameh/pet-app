<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $chats = Chat::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->with(['user1', 'user2', 'messages'])
            ->get();

        return response()->json($chats);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $chat = Chat::create([
            'user1_id' => $user->id,
            'user2_id' => $request->input('user2_id')
        ]);

        return response()->json($chat, 201);
    }
}