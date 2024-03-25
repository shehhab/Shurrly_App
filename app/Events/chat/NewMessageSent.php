<?php

namespace App\Events\chat;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $advisor_id;
    public $seeker_id;
    public $chat;

    public $isSeeker;
    /**
     * New Message Sent  constructor
     * @param  ChatMessage $chatMessage
     */
    public function __construct($message, $chat, $isSeeker)
    {
        $this->message = $message;
        // $this->advisor_id = $advisor_id;
        // $this->seeker_id = $seeker_id;
        $this->chat = $chat;
        $this->isSeeker = $isSeeker;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\PresenceChannel
     */
    // 'channel' : 'chat.${chatId}.${seekerId}.${advisorId}'
    // 'event' :'chat-message'
    public function broadcastOn()
    {
        // chat.chatID.chatSeekerId.chatAdvisorId
        return new PresenceChannel('chat.' . $this->chat->id . '.' . $this->chat->seeker_id . '.' . $this->chat->advisor_id);
    }

    /**
     * broadcastAs event name
     * @return  string
     */
    public function broadcastAs(): string
    {
        return "chat-message";
    }
    /**
     * Data sending Back To Client
     *@return array
     */

    public function broadcastWith()
    {
        return [
            'id' => $this->chat->id,
            'seeker' => $this->chat->seeker ? [
                'id' => $this->chat->seeker->id,
                'name' => $this->chat->seeker->name
            ] : null,
            'advisor' => $this->chat->advisor ? [
                'id' => $this->chat->advisor->id,
                'name' => $this->chat->advisor->name
            ] : null,
            'message' => $this->message,
            'isSeekerMessage' => $this->isSeeker

        ];
    }
}
