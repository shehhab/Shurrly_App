<?php

namespace App\Http\Controllers\Api\core\Chat;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Block;
use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;


class GetAllChatController extends Controller
{
    public function __invoke(Request $request)
    {
        $userId = auth()->user()->id;
        $isSeeker = $request->header('isSeeker');

        $chats = Chat::with(['chat_messages', 'advisor']) // Load advisor data
            ->where(function ($query) use ($userId) {
                $query->where('seeker_id', $userId)
                    ->orWhere('advisor_id', $userId);
            })
            ->whereHas('chat_messages', function ($query) use ($isSeeker) {
                $query->where('isSeeker', $isSeeker);
            })
            ->with(['lastMessages'])
            ->get();

        $blockedUsers = Block::where('user_id', $userId)
            ->orWhere('blocked_user_id', $userId)
            ->get()
            ->pluck('blocked_user_id');

        $chats->transform(function ($chat) use ($blockedUsers) {
            $advisor = $chat->advisor;

            if (!$advisor) {
                return null; // Skip if no advisor is found
            }

            $this->formatChatTimestamps($chat);

            $advisorModel = Advisor::find($chat->advisor_id);
            $mediaUrl = $advisorModel ? $advisorModel->getFirstMediaUrl('advisor_profile_image') : null;
            return [
                'id' => $chat->id,
                'advisor_id' => $chat->advisor_id,
                'name' => $advisor->name,
                'image' => $mediaUrl ?: null, // Ensure null if no media found
                'is_blocked' => $blockedUsers->contains($chat->seeker_id) || $blockedUsers->contains($chat->advisor_id),
                'time_chat_formatted' => $chat->time_chat_formatted,
                'date_chat_formatted' => $chat->date_chat_formatted,
                'last_messages' => $chat->lastMessages,
            ];
        }); // Remove null entries if any

        return $this->handleResponse(
            data: ['chats' => $chats],
            code: 200
        );
            }


    private function formatChatTimestamps($chat)
    {
        $chat->time_chat_formatted = $chat->created_at->format('h:i:s A');
        $chat->date_chat_formatted = $chat->created_at->isoFormat('ddd, DD/MM/YYYY');

        if ($chat->lastMessages) {
            $lastMessageCreatedAt = Carbon::parse($chat->lastMessages->created_at);
            $chat->lastMessages->date_formatted = $lastMessageCreatedAt->isToday() ? 'Today' : ($lastMessageCreatedAt->isYesterday() ? 'Yesterday' : $lastMessageCreatedAt->isoFormat('ddd, DD/MM/YYYY'));
            $chat->lastMessages->time_formatted = $lastMessageCreatedAt->format('h:i:s A');

            unset($chat->lastMessages->created_at, $chat->lastMessages->updated_at);
        }

        unset($chat->updated_at, $chat->created_at);
    }
}
