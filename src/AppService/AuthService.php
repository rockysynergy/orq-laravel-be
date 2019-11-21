<?php

namespace Orq\Laravel\Starter\AppService;

use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    /**
     * 注册新用户
     */
    public static function register(string $wxopenId, string $pwd):User
    {
        $user = new User();
        $user->wxopenid = $wxopenId;
        $user->password = Hash::make($pwd);
        $user->save();

        return $user;
    }

    /**
     * 获取用户的微信openid
     */
    public static function getWxOpenId(string $code):string
    {
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code';
        $url = sprintf($url, config('site.wxmp.app_id'), config('site.wxmp.app_secret'), $code);
        $res = self::request($url);
        $res = json_decode($res, true);

        if (isset($res['openid'])) {
            return $res['openid'];
        } else {
            Log::error('WxResponse is: '.json_encode($res));
            throw new AppException('获取openid失败!', 1563176425);
        }

    }

    /**
     * 发送HTTP请求，如果data为null会使用post方法
     */
    protected static function request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
