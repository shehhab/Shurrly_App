<?php

namespace App\Events\chat;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * New Message Sent  constructor
     * @param  ChatMessage $chatMessage
     */
    public function __construct(private ChatMessage $chatMessage)
    {
        //
    }

 /**
 * Get the channels the event should broadcast on.
 *
 * @return \Illuminate\Broadcasting\PresenceChannel
 */
public function broadcastOn()
{
    return new PresenceChannel('chat.'.$this->chatMessage->chat_id);
}

/**
 * broadcastAs event name
 * @return  string
 */
public function broadcastAs() :string
{
    return "message.sent";
}
/**
 * Data sending Back To Client
 *@return array
 */

public function broadcastWith() :array
{
    return [
        'chat_id'=>$this->chatMessage->chat_id,
        'message'=>$this->chatMessage->toArray(),
    ];
}


}
