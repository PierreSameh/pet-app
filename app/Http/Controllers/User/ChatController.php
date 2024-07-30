<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatRequest;
use App\Models\Notification;
use App\HandleTrait;

class ChatController extends Controller
{
    use HandleTrait;
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
        $rejected = ChatRequest::where('sender_id', $user->id)
        ->where('receiver_id', $request->receiver_id)
        ->where('status', 'rejected')->get();
        if(count($rejected) > 0 ) {
            return $this->handleResponse(
                false,
                "You Can't Send Messages to this user",
                [],
                [],
                []
            );
        }
        $chatRequest = ChatRequest::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->input('receiver_id')
        ]);

        // Optionally, you can send a notification to the receiver here
        if(isset($chatRequest)) {
        $sender = $request->user()->first();
        $senderName = $sender->first_name . " " . $sender->last_name;
        $notification = new Notification();
        $notification->sender_id = $chatRequest->sender_id;
        $notification->receiver_id = $chatRequest->receiver_id;
        $notification->content = "Message Request From " . $senderName;
        $notification->is_opened = 0;
        $notification->save();
        }


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

        if(isset($chat)) {
            $notification = Notification::where('sender_id', $chatRequest->sender_id)
            ->where('receiver_id', $chatRequest->receiver_id)->latest()->first();
            $notification->is_opened = 1;
            $notification->save();
        }

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

        if($chatRequest->status == 'rejected') {
            $notification = Notification::where('sender_id', $chatRequest->sender_id)
            ->where('receiver_id', $chatRequest->receiver_id)->latest()->first();
            $notification->is_opened = 1;
            $notification->save();
        }


        return response()->json(['message' => 'Chat request rejected'], 200);
    }

    public function getNotifications(Request $request) {
        $user = $request->user();
        $notifications = Notification::where('receiver_id', $user->id)->get();
        if (count($notifications) == 0) {
            return $this->handleResponse(
                false,
                'You Have No Notifications',
                [],
                [],
                []
            );
        }
        return $this->handleResponse(
            true,
            '',
            [],
            [$notifications],
            [],
        );
    }
}