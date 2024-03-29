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
        $limit = $request->limit ?? 25;
        Chat::where('seeker_id', auth()->user()->id)->with(['messages', 'lastMessages'])->paginate($limit);

    }
    public function sendMessage(Request $request)
    {
        if (auth()->user()->id === $request->seeker) return $this->handleResponse(message: 'Cannot send to yourself');
        $chat = Chat::firstOrCreate([
            'seeker_id' => auth()->user()->id,
            'advisor_id' => $request->advisor
        ]);
        ChatMessage::create(['chat_id' => $chat->id, 'message' => $request->message, 'isSeeker' => true]);
        event(new NewMessageSent($request->message, $chat, true));

        return $this->handleResponse(message: 'Message Sent');
    }
}
