<?php
namespace Vendor\Wechat;
use Vendor\Wechat\Wechat;


/**
* 微信Wxauth认证
*  return 
*  $_SESSION['wxuser'];
*  $_SESSION['open_id'];
*  $this->open_id;
*/
class Wxauth {

    private $options;
    public $open_id;
    public $wxuser;

    public function __construct($options) {
        if($options){
            $this->options = $options;
        }else if(S('WECHATADDONS_CONF')){
            $this->options = S('WECHATADDONS_CONF');
        }else{
            die('没有填写appid和appsecret');
        }
        $this->wxoauth();
        session_start();
    }

    public function wxoauth($scope) {
        $scope ? $scope = 'snsapi_base' : $scope = 'snsapi_userinfo';
        $code = isset($_GET['code']) ? $_GET['code'] : '';
        $token_time = isset($_SESSION['token_time']) ? $_SESSION['token_time'] : 0;
        if (!$code && isset($_SESSION['open_id']) && isset($_SESSION['user_token']) && $token_time > time() - 3600) {
            if (!$this->wxuser) {
                $this->wxuser = $_SESSION['wxuser'];
            }
            $this->open_id = $_SESSION['open_id'];
            return $this->open_id;
        } else {
            $options = array(
                'token' => $this->options["token"], //填写你设定的key
                'appid' => $this->options["appid"], //填写高级调用功能的app id
                'appsecret' => $this->options["appsecret"] //填写高级调用功能的密钥
            );
            $we_obj = new Wechat($options);
            if ($code) {
                $json = $we_obj->getOauthAccessToken();
                if (!$json) {
                    unset($_SESSION['wx_redirect']);
                    die('获取用户授权失败，请重新确认');
                }
                $_SESSION['open_id'] = $this->open_id = $json["openid"];
                $access_token = $json['access_token'];
                $_SESSION['user_token'] = $access_token;
                $_SESSION['token_time'] = time();
                $userinfo = $we_obj->getUserInfo($this->open_id);
                if ($userinfo && !empty($userinfo['nickname'])) {
                    $this->wxuser = array(
                        'open_id' => $this->open_id,
                        'nickname' => $userinfo['nickname'],
                        'sex' => intval($userinfo['sex']),
                        'location' => $userinfo['province'] . '-' . $userinfo['city'],
                        'avatar' => $userinfo['headimgurl']
                    );
                } elseif (strstr($json['scope'], 'snsapi_userinfo') !== false) {
                    $userinfo = $we_obj->getOauthUserinfo($access_token, $this->open_id);
                    if ($userinfo && !empty($userinfo['nickname'])) {
                        $this->wxuser = array(
                            'open_id' => $this->open_id,
                            'nickname' => $userinfo['nickname'],
                            'sex' => intval($userinfo['sex']),
                            'location' => $userinfo['province'] . '-' . $userinfo['city'],
                            'avatar' => $userinfo['headimgurl']
                        );
                    } else {
                        return $this->open_id;
                    }
                }
                if ($this->wxuser) {
                    $_SESSION['wxuser'] = $this->wxuser;
                    $_SESSION['open_id'] = $json["openid"];
                    unset($_SESSION['wx_redirect']);
                    return $this->open_id;
                }
            }
            if (!isset($_SESSION['wx_redirect'])) {
                $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "?refer_id=" . $this->options['referId'];
                $_SESSION['wx_redirect'] = $url;
            } else {
                $url = $_SESSION['wx_redirect'];
            }
            if (!$url) {
                unset($_SESSION['wx_redirect']);
                die('获取用户授权失败');
            }
            $oauth_url = $we_obj->getOauthRedirect($url, "wxbase", $scope);
            header('Location: ' . $oauth_url);
        }
    }

}
