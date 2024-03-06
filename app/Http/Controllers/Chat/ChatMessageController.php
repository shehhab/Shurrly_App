<?php

namespace App\Http\Controllers\Chat;

use App\Models\Chat;
use App\Models\Seeker;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Events\chat\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\chat\GetMessageRequest;
use App\Http\Requests\chat\StoreMessageRequest;

class ChatMessageController extends Controller
{
    /**
     * Get chat message
     * @param  GetMessageRequest $request
     * @return JsonResponse
     */

     //m Used to retrieve messages associated with a specific conversation.
    public function index(GetMessageRequest $request):JsonResponse
    {
        $data = $request->validated();
        $chatId = $data['chat_id'];
        $currentPage = $data['page'];
        $pagesize = $data['page_size']?? 15 ;

        $messages = ChatMessage::where('chat_id', $chatId)
        ->with('seeker')
        ->latest('created_at')
        ->simplePaginate(
            $pagesize,
            ['*'],
            'page',
            $currentPage
        );
        return $this->handleResponse(data:$messages->getCollection());


    }

    /**
     * create chat message
     * @param StoreMessageRequest $request
     * @return JsonResponse
     */

     //This function saves new messages in the database and downloads the sender's data
    public function store(StoreMessageRequest $request) :JsonResponse
    {
        $data = $request->validated();
        $data['seeker_id']= auth()->user()->id;
        $chatMessage =  ChatMessage::create($data);

        $chatMessage->load('seeker');

        //! to do  send broadcast event  to pusher and send notification  to onesignal servies
        $this->sendNotificationToOther($chatMessage);

        return  $this->handleResponse(data:$chatMessage , message: 'message has been succseefully');

    }
    /**
     *send notification to other users
     * @param ChatMessage $chatMessage
     */

     //This function broadcasts the event to notify other subscribers of the presence of a new message
    private function sendNotificationToOther(ChatMessage $chatMessage){
        broadcast(new NewMessageSent($chatMessage))->toOthers();
        $seeker = auth()->user();
        $seekerId = $seeker->id ;
        $chat = Chat::where('id', $chatMessage->chat_id)
        ->with(['participants'=>function($query) use ($seekerId) {
            $query->where('seeker_id','!=',$seekerId
        );
        }])
        ->first();
        if(count($chat->participants)>0){
            $otherUserId = $chat->participants[0]->seeker_id;
            $otherUser = Seeker::where('id',$otherUserId)->first();
            $otherUser->sendNewMessageNotification([
                'messageData'=>[
                    'senderName'=>$seeker->username,
                    'message' =>$chatMessage->message,
                    'chatId' =>$chatMessage->chat_id,

            ]
            ]);


        }
    }
}
