<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait AuthResponse
{
    public function RegisterResponse($user, $token)
    {
        $response = [
            'message' => 'Successfully Created Account , Verify Your Email please',
            'data' => array_merge($user->toArray(request()), ['token' => $token]),
            'status' => true,
            'code' => Response::HTTP_CREATED
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }
    public function ErrorRegisterResponse($msg)
    {
        $response = [
            'message' => $msg,
            'status' => false,
            'code' => Response::HTTP_UNAUTHORIZED
        ];
        return response()->json($response, Response::HTTP_UNAUTHORIZED);
    }

    public function LoginResponse($user, $token)
    {
        $response = [
            'message' => 'Successfully Login',
            'data' => array_merge($user->toArray(request()), ['token' => $token]),
            'status' => true,
            'code' => Response::HTTP_OK
        ];
        return response()->json($response, Response::HTTP_OK);
    }
    public function ValidationFailureResponse($errors)
    {
        $formattedErrors = [];
        foreach ($errors->toArray() as $field => $messages) {
            $formattedErrors[$field] = implode(' ', $messages);
        }
        $response = [
            'errors' => $formattedErrors,
            'message' => 'validation errors',
            'status' => false,
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];
        return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function OTP_Error_Response()
    {
        $response = [
            'message' => 'OTP does not exist Or Expired',
            'status' => false,
            'code' => Response::HTTP_UNAUTHORIZED
        ];
        return response()->json($response, Response::HTTP_UNAUTHORIZED);
    }
    public function EmailVerifiedResponse($email)
    {
        $response = [
            'message' => 'Email: (' . $email . ') Verified Successfully',
            'status' => true,
            'code' => Response::HTTP_OK
        ];
        return response()->json($response, Response::HTTP_OK);
    }
    public function ErrorLoginResponse($msg)
    {
        $response = [
            'message' => $msg,
            'status' => false,
            'code' => Response::HTTP_UNAUTHORIZED
        ];
        return response()->json($response, Response::HTTP_UNAUTHORIZED);
    }
    public function OKResponse($msg)
    {
        $response = [
            'message' => $msg,
            'status' => true,
            'code' => Response::HTTP_OK
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function passwordMismatchResponse()
    {
        $response = [
            'message' => 'Current password does not match.',
            'status' => false,
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];
        return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
