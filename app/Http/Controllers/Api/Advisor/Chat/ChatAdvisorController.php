<?php

namespace App\Http\Controllers\Api\Advisor\Chat;

use App\Events\chat\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatAdvisorController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->limit ?? 25;
        Chat::where('advisor_id', auth()->user()->id)->with(['messages', 'lastMessages'])->paginate($limit);
    }
    public function sendMessage(Request $request)
    {
        if (auth()->user()->id === $request->seeker) return $this->handleResponse(message: 'Cannot send to yourself');
        $chat = Chat::firstOrCreate([
            'advisor_id' => auth()->user()->id,
            'seeker_id' => $request->seeker
        ]);
        ChatMessage::create(['chat_id' => $chat->id, 'message' => $request->message, 'isSeeker' => false]);
        event(new NewMessageSent($request->message, $chat, false));

        return $this->handleResponse(message: 'Message Sent');
    }
}
