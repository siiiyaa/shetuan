<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\TokenController;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Support\Facades\Cookie;

class CheckTeacherToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
         *  1.登录成功生成token并返回前端存cookie
            2.前端提交cookie后台获取cookie即token
            3.验证token {
                   1.如果过了第一次时间没有过第二次时间，则解开他的token并拿信息去生成一个新token，然后响应回去前端替换
                   2.如果两次时间都过期了退出登录
              }
        */

        $token = $request->header('authorization');

        if($token){
            $token = substr($token,7);

            if(strpos($token,'\\')){
                $token = str_replace('\\','',$token); //去掉字符串中的\,因为有\是解析不了的
            }

            $jwt = json_decode($token);

            $access_jwt = $jwt->access_jwt;
            $refresh_jwt = $jwt->refresh_jwt;

            $key = config('app.key');

            try{
                $jwt = JWT::decode($access_jwt,$key,['HS256']);
                if($jwt->token_data->token_rule != 'teacher'){
                    return response()->json(['data'=>'', 'code'=> 401, 'message'=>'令牌错误']);
                }
            } catch (SignatureInvalidException $e) {
                return response()->json(['data'=>'', 'code'=> 401, 'message'=>'令牌错误 请重新登录']);
            } catch (ExpiredException $expiredException) {
                try {
                    $jwt = JWT::decode($refresh_jwt,$key,['HS256']);
                    if($jwt->token_data->token_rule != 'teacher'){
                        return response()->json(['data'=>'', 'code'=> 401, 'message'=>'令牌错误']);
                    }
                    $new_token = TokenController::getToken(collect($jwt->token_data)->toArray());
                } catch (SignatureInvalidException $signatureInvalidException) {
                    return response()->json(['data'=>'', 'code'=> 401, 'message'=>'令牌错误 请重新登录']);
                } catch (ExpiredException $expiredException) {
                    return response()->json(['data'=>'', 'code'=> 401, 'message'=>'令牌过期 请重新登录']);
                }
            }

            $token_data = ['token_data' => $jwt->token_data];
            $request->attributes->add($token_data);

            $response =  $next($request);

            if(isset($new_token)){
               $request->headers->set('Authorization', 'Bearer '.$new_token);
            }

            return $response;
        }else{
            return response()->json(['data'=>'', 'code'=> 401, 'message'=>'没有令牌 请重新登录']);
        }


    }
}
