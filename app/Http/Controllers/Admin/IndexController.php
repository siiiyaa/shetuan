<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\TokenController;
use App\Http\Requests\Admin\LoginRequest;
use App\models\Admin;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Storage;

class IndexController
{
    use BaseTrait;


    public function login(LoginRequest $request, Admin $admins)
    {
        $data = $request->all();

        $user = $admins->where('ad_name', $data['name'])->first();
        if($user){
            if(password_verify($data['password'], $user->password)){
                $user_result = collect($user)->only(['id','ad_name'])->toArray();
                $user_result['token_rule'] = 'admin';
                $token['token'] = TokenController::getToken($user_result);

                return $this->result($token, 1,'登录成功',200);
            } else {
                return $this->result('',0,'登录失败',200);
            }
        }else{
           return $this->result('',0,'用户不存在',200);
        }

    }
















}
