<?php

namespace App\Http\Controllers\Api\core\Chat;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Block;
use App\Models\Seeker;
use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DateTime; // Import DateTime clas
use Illuminate\Database\Eloquent\Builder;


class GetAllChatController extends Controller
{
    public function __invoke(Request $request)
    {
        $userId = auth()->user()->id;

        $isSeeker = $request->header('isSeeker');
        $perPage =  6;
        $chats = Chat::with(['chat_messages', 'advisor']) // Load advisor data
            ->where(function ($query) use ($userId) {
                $query->where('seeker_id', $userId)
                    ->orWhere('advisor_id', $userId);
            })
            ->whereHas('chat_messages', function ($query) use ($isSeeker) {
                $query->where('isSeeker', $isSeeker);
            })
            ->with(['lastMessages'])
            ->paginate($perPage);

        $blockedUsers = Block::where('user_id', $userId)
            ->orWhere('blocked_user_id', $userId)
            ->get()
            ->pluck('blocked_user_id');

            $chats->transform(function ($chat) use ($blockedUsers , $userId) {

                $this->formatChatTimestamps($chat);

            if ($chat->advisor_id == $userId) {

                $seeker = Seeker::find($chat->seeker_id);

                $media = $seeker->getFirstMediaUrl('seeker_profile_image');
                if (!$media) {

                    $media = asset('Default/profile.jpeg');
                }

                return [
                    'id' => $chat->id,
                    'name' => $chat->seeker->name,
                    'advisor_id' => $chat->seeker->id,
                    'image' => $media ?: null, // Ensure null if no media found
                    'is_blocked' => $blockedUsers->contains($chat->seeker_id) || $blockedUsers->contains($chat->advisor_id),
                    'time_chat_formatted' => $chat->time_chat_formatted,
                    'date_chat_formatted' => $chat->date_chat_formatted,
                    'last_messages' => $chat->lastMessages,
                ];
            }


            if ($chat->seeker_id == $userId)
            {
                $seeker = Seeker::find($chat->advisor_id);
                $advisor = $seeker->advisor;

                $media = $advisor->getFirstMediaUrl('advisor_profile_image');
                if (!$media) {
                    $media = $seeker->getFirstMediaUrl('seeker_profile_image');
                }
                if (!$media) {

                    $media = asset('Default/profile.jpeg');
                }

                return [
                    'id' => $chat->id,
                    'name' => $chat->advisor->name,
                    'advisor_id' => $chat->advisor->id,
                    'image'  =>$media ,
                    'is_blocked' => $blockedUsers->contains($chat->seeker_id) || $blockedUsers->contains($chat->advisor_id),
                    'time_chat_formatted' => $chat->time_chat_formatted,
                    'date_chat_formatted' => $chat->date_chat_formatted,
                    'last_messages' => $chat->lastMessages,
                ];
            }
            });

        $paginationData = $this->pagination($chats);
        return $this->handleResponse(
            data: [
                'chats' => $chats->items(),
                'pagination' => $paginationData
            ],
            code: 200
        );
            }

            private function formatChatTimestamps($chat)
            {
                $chat->time_chat_formatted = $chat->created_at->format('h:i A');
                $chat->date_chat_formatted = $chat->created_at->isoFormat('ddd, DD/MM/YYYY');

                if ($chat->lastMessages) {
                    $lastMessageCreatedAt = Carbon::parse($chat->lastMessages->created_at);
                    $chat->lastMessages->date_formatted = $lastMessageCreatedAt->isToday() ? 'Today' : ($lastMessageCreatedAt->isYesterday() ? 'Yesterday' : $lastMessageCreatedAt->isoFormat('ddd, DD/MM/YYYY'));
                    $chat->lastMessages->time_formatted = $this->daysBetween($lastMessageCreatedAt); // Use daysBetween function for time formatting

                    unset($chat->lastMessages->created_at, $chat->lastMessages->updated_at);
                }

                unset($chat->updated_at, $chat->created_at);
            }

            private function daysBetween($postDate)
            {
                $now = new DateTime();
                $difference = $now->diff($postDate);

                $days = $difference->d;
                $hours = $difference->h;
                $minutes = $difference->i;

                if ($days == 0) {
                    if ($hours == 0) {
                        if ($minutes == 0) {
                            return 'now';
                        } else {
                            return $minutes . ' minutes';
                        }
                    } else {
                        return $hours . ' hours';
                    }
                } else {
                    return $days . ' days';
                }
            }
        }
