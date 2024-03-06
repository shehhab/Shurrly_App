<?php
namespace App\Traits;

use Illuminate\Http\Response;

trait ResponseTrait {
    public function handleResponse($view = null,$status = true , $message = 'Success',$code = Response::HTTP_OK, $data=null , $pagination =false ,$isError=false, $errors = null){

        if(!request()->wantsJson()) abort(404);
        $response = [
            'status' => $status,
            'code' => $code,
            'message' => $message
        ];

        if($data) $response['data'] = $data;
        if($pagination) $response['pagination'] = $this->pagination($data);
        if($isError) $response['errors'] = $errors;

        return response()->json($response,$code);
    }

    public function pagination($collection){
        return [
            'total' => $collection->total(),
            'per_page' => $collection->perPage(),
            'current_page' => $collection->currentPage(),
            'last_page' => $collection->lastPage(),
            'from' => $collection->firstItem(),
            'to' => $collection->lastItem(),
        ];
    }
}
