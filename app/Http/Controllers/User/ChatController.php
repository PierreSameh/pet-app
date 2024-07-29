<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatRequest;

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

        $chatRequest = ChatRequest::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->input('receiver_id')
        ]);

        // Optionally, you can send a notification to the receiver here

        return response()->json($chatRequest, 201);
    }

    public function acceptRequest($id, Request $request)
    {
        $chatRequest = ChatRequest::find($id);

        if (!$chatRequest || $chatRequest->receiver_id != $request->user()->id) {
            return response()->json(['error' => 'Invalid request'], 403);
        }

        $chatRequest->status = 'accepted';
        $chatRequest->save();

        $chat = Chat::create([
            'user1_id' => $chatRequest->sender_id,
            'user2_id' => $chatRequest->receiver_id
        ]);

        return response()->json($chat, 201);
    }

    public function rejectRequest($id, Request $request)
    {
        $chatRequest = ChatRequest::find($id);

        if (!$chatRequest || $chatRequest->receiver_id != $request->user()->id) {
            return response()->json(['error' => 'Invalid request'], 403);
        }

        $chatRequest->status = 'rejected';
        $chatRequest->save();

        return response()->json(['message' => 'Chat request rejected'], 200);
    }
}