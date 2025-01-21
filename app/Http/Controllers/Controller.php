<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth as Author;

class Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;
    public $user;
    public function __construct()
    {
        $this->init();
    }
    public function init()
    {
        global $user;
        $user = Author::user();
        if ($user) {
            $this->user = $user;
        }
    }
    public function validate($request, $rules, $messages = [], $customAttributes = [])
    {
        $validator = \Validator::make($request->all(), $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            $arr = $validator->errors()->toArray();
            $data = [];
            foreach ($arr as $k => $v) {
                $data[$k] = $v[0];
            }
            return $this->error($validator->errors()->first(), $data);
        } else {
            return '';
        }
    }
    public function success($message = '', $data = [])
    {
        return response()->json([
            'code' => 0,
            'message' => $message,
            'data' => $data,
            'type' => 'success',
        ]);
    }

    public function error($message = '', $data = [])
    {
        return  response()->json([
            'code' => 250,
            'message' => $message,
            'data' => $data,
            'type' => 'error',
        ]);
    }

}
