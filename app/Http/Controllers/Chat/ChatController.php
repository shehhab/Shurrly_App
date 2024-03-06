<?php

namespace App\Http\Controllers\Chat;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\chat\GetChatRequest;
use App\Http\Requests\chat\StorChatRequest;
use App\Http\Requests\chat\StoreChatRequest;

class ChatController extends Controller
{
    /**
     *Gets chats
     *
     *@param GetChatRequest $request
     *@return JsonResponse
     */





    public function index(GetChatRequest $request){
        $data = $request->validated();
        $isPrivate = 1 ;
        if($request->has('is_private')){
            $isPrivate = (int)$data['is_private'];
        }

        $chats = Chat::where('is_private',$isPrivate)
        ->hasParticipant(auth()->user()->id)
        ->whereHas('messages')
        ->with('lastMessages.seeker', 'participants.seeker')->latest('updated_at')
        ->get()
        ;
        return $this->handleResponse(data: $chats);


    }

    /**
     *stores a new chat
     * @param StoreChatRequest $request
     *@return JsonResponse
     */


        // to store a new chat
     public function store(StoreChatRequest $request): JsonResponse
     {
         $data = $this->prepareStoreData($request);

         if ($data['seekerId'] === $data['otherSeekerId']) {
             return $this->error('You cannot create a chat with yourself.');
         }

         $previousChat = $this->getPreviousChat($data['otherSeekerId']);

         if ($previousChat === null) {
             $chat = Chat::create($data['data']);
             $chat->participants()->createMany([
                 ['seeker_id' => $data['seekerId']],
                 ['seeker_id' => $data['otherSeekerId']]
             ]);

             $chat->refresh()->load('lastMessages.seeker', 'participants.seeker');
             return $this->handleResponse($chat);
         }

         return $this->handleResponse(data:$previousChat->load('lastMessages.seeker', 'participants.seeker'));
     }

    /**
     * check if user and another user has previous chat or not
     *@param int $otherSeekerId
     * @return mixed
     */
    private function getPreviousChat(int $otherSeekerId): mixed
    {
        $seekerId = auth()->user()->id;

        return Chat::where('is_private', 1)
            ->whereHas('participants', function ($query) use ($seekerId) {
                $query->where('seeker_id', $seekerId);
            })
            ->whereHas('participants', function ($query) use ($otherSeekerId) {
                $query->where('seeker_id', $otherSeekerId);
            })
            ->first();
    }


    /**
     *prepares data for storage chat
     *
     *@param StoreChatRequest $request
     *@return array
     */
    private function prepareStoreData(StoreChatRequest $request) :array
    {
        $data = $request->validated();
        $otherSeekerId = (int)$data['seeker_id'];
        unset($data['seeker_id']);
        $data['created_by'] = auth()->user()->id;

        return [
            'otherSeekerId' => $otherSeekerId,
            'seekerId' => auth()->user()->id,
            'data' => $data ,
        ];
    }

    /**
     *@param chat $chat
     * @return JsonResponse
     */
    public function show(Chat $chat):JsonResponse
    {

        $previousChat =$chat->load('lastMessages.seeker','participants.seeker');
        return $this->handleResponse(data: $previousChat->load('lastMessages.seeker', 'participants.seeker'));

    }
}
