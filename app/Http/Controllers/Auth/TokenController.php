<?php
namespace App\Http\Controllers\Auth;

use Firebase\JWT\JWT;

class TokenController
{
    public static function getToken(array $data)
    {
        //盐值，也称私钥
        $key = config('app.key');

        $token = [
            'iss' => 'guo',
            'iat' => time(),
            'token_data' => $data
        ];

        $access_token = $token;
        $access_token['exp'] = time() + 3600;

        $refresh_token = $token;
        $refresh_token['exp'] = time() + 7200;

        $access_jwt = JWT::encode($access_token, $key, 'HS256');
        $refresh_jwt = JWT::encode($refresh_token, $key, 'HS256');

        $big_jwt = [
            'access_jwt' => $access_jwt,
            'refresh_jwt' => $refresh_jwt,
            'token_type' => 'bearer' //token_type：表示令牌类型，该值大小写不敏感，这里用bearer
        ];

        return json_encode($big_jwt, JSON_FORCE_OBJECT);
    }
}
