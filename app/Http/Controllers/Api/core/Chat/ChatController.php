<?php

namespace App\Http\Controllers\Api\core\Chat;

use App\Events\chat\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController  extends Controller
{
    protected $isSeeker;
    protected $authUser;
    protected $mainKey;
    protected $secondaryKey;

    public function __construct()
    {

        $this->isSeeker = request()->header('isSeeker');
        if ($this->isSeeker) {
            $this->mainKey =   'seeker_id';
            $this->secondaryKey =   'advisor_id';
        } else {
            $this->mainKey =   'advisor_id';
            $this->secondaryKey =   'seeker_id';
        }
    }
    public function index(Request $request)
    {
        $limit = $request->limit ?? 25;
        $chat = Chat::where($this->mainKey, auth()->user()->id)->with(['messages', 'lastMessages'])->paginate($limit);
        return $this->handleResponse(data: $chat);
    }
    public function sendMessage(Request $request)
    {
        if (auth()->user()->id === $request->userId) return $this->handleResponse(message: 'Cannot send to yourself');
        $chat = Chat::firstOrCreate([
            $this->mainKey => auth()->user()->id,
            $this->secondaryKey => $request->userId
        ]);
        ChatMessage::create(['chat_id' => $chat->id, 'message' => $request->message, 'isSeeker' => $this->isSeeker]);
        event(new NewMessageSent($request->message, $chat, $this->isSeeker));

        return $this->handleResponse(message: 'Message Sent');
    }
}
