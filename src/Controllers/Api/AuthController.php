<?php
namespace Orq\Laravel\Starter\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Orq\Laravel\Starter\AppService\AuthService;

class AuthController extends Controller
{
    public $successStatus = 200;

     /**
     * 微信登录
     * 如果没有uer记录则创建用户
     *
     * @return [accessToken, user_id]
     */
    public function wxlogin()
    {
        $openid = request('wxopenid') ?? AuthService::getWxOpenId(request('code'));
        if (Auth::attempt(['wxopenid'=>$openid, 'password'=>request('pwd')])) {
            $user = Auth::user();
        } else {
            // 注册用户
            $user = AuthService::register($openid, request('pwd'));
        }

        // 如果没有accessToken则重新生成
        $cToken = $user->token();
        if (is_null($cToken) && request('mode') != 'test') {
            $token = $user->createToken('AppName')->accessToken;
        } else {
            $token = $cToken;
        }
        return response()->json(['code'=>0, 'msg'=>'success', 'token'=>$token, 'userId'=>$user->id], $this->successStatus);
    }
}
