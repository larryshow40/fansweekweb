<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function resourceSuccess($message, $data)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], 200);
    }

    protected function resourceError($message)
    {
        return response()->json(['status' => 'error', 'message' => $message], 400);
    }

    protected function returnToken($user, $request = null)
    {
        $tokenResult = $user->createToken('Enterprise Personal Access Client');
        $token = $tokenResult->token;
        if ($request && $request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(49);
        }
        $token->save();
        $user->access_token = $tokenResult->accessToken;
        $user->token_type = 'Bearer';
        $user->update();
        return $this->resourceSuccess('User Authenticated Successfully', ($user));
    }
}
