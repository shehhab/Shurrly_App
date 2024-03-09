<?php

namespace App\Http\Controllers\Api\Seeker\Chat;

use App\Events\chat\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
    }
    public function sendMessage(Request $request)
    {
        $chat = Chat::firstOrCreate([
            'seeker_id' => auth()->user()->id,
            'advisor_id' => $request->advisor_id
        ]);
        ChatMessage::create(['chat_id' => $chat->id, 'message' => $request->message, 'isSeeker' => true]);
        event(new NewMessageSent($request->message, $chat, true));

        return $this->handleResponse(message: 'Message Sent');
    }
}
