<?php

namespace Org\Util;

/* * *
 * 阿里大鱼短信发送
 */

class SmsAlidayu {

    /**
     * 正式环境API请求地址
     */
    const API_URI = 'http://gw.api.taobao.com/router/rest';

    /**
     * 沙箱环境API请求地址
     */
    const SANDBOX_URI = 'http://gw.api.tbsandbox.com/router/rest';

    /**
     * App Key
     * 
     * @var string
     * @link http://my.open.taobao.com/ 请到此处申请
     */
    protected $appKey;

    /**
     * App Secret
     * 
     * @var string
     * @link http://my.open.taobao.com/ 请到此处申请
     */
    protected $appSecret;

    /**
     * api请求地址
     * @var string
     */
    protected $apiURI;

    /**
     * API请求参数
     * @var array
     */
    protected $params = array(
        'method' => 'alibaba.aliqin.fc.sms.num.send',
    );

    /**
     * 构造方法
     * 
     * @param string $appKey    [description]
     * @param string $appSecret [description]
     */
    public function __construct($appKey = '', $appSecret = '') {
        $this->appKey = $appKey ? : C('AlidayuAppKey');
        $this->appSecret = $appSecret ? : C('AlidayuAppSecret');
        $this->apiURI = C('AlidayuApiEnv') ? self::API_URI : self::SANDBOX_URI;
    }

    /**
     * 设置AppKey
     * @param string $value AppKey
     */
    public function setAppKey($value) {
        $this->appKey = $value;
    }

    /**
     * 设置App Secret
     * @param string $value AppSecret
     */
    public function setAppSecret($value) {
        $this->appSecret = $value;
    }

    /**
     * 执行请求
     * @param  Object $request 请求参数
     * @return array|false           
     */
    public function execute($request) {
        $params = $request->getParams();
        $params = array_merge($this->publicParams(), $params);

        $params['sign'] = $this->sign($params);

        $req = $this->curl($this->apiURI, $params);
        return json_decode($req, true);
    }

    /**
     * 公共参数
     * @return array 
     */
    protected function publicParams() {
        return array(
            'app_key' => $this->appKey,
            'timestamp' => date('Y-m-d H:i:s'),
            'format' => 'json',
            'v' => '2.0',
            'sign_method' => 'md5'
        );
    }

    /**
     * 签名
     * @param  array $params 参数
     * @return string         
     */
    public function sign($params) {
        ksort($params);

        $tmps = array();
        foreach ($params as $k => $v) {
            $tmps[] = $k . $v;
        }

        $string = $this->appSecret . implode('', $tmps) . $this->appSecret;

        return strtoupper(md5($string));
    }

    /**
     * curl请求
     * @param  string $url        string
     * @param  array|null $postFields 请求参数
     * @return [type]             [description]
     */
    public function curl($url, $postFields = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //https 请求
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (is_array($postFields) && 0 < count($postFields)) {
            $postBodyString = "";
            foreach ($postFields as $k => $v) {
                $postBodyString .= "$k=" . urlencode($v) . "&";
            }
            unset($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);

            $header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
        }

        $reponse = curl_exec($ch);
        return $reponse;
    }

    /**
     * SmsNumSend.class.php
     */

    /**
     * [可选]设置公共回传参数，在“消息返回”中会透传回该参数；举例：用户可以传入自己下级的会员ID，在消息返回时，该会员ID会包含在内，用户可以根据该会员ID识别是哪位会员使用了你的应用
     * 
     * @param string $value 
     */
    public function setExtend($value) {
        $this->params['extend'] = $value;
        return $this;
    }

    /**
     * [必须]设置短信类型，传入值请填写normal
     * 
     * @param string $value 
     */
    public function setSmsType($value) {
        $this->params['sms_type'] = $value;
        return $this;
    }

    /**
     * [必须]设置短信签名，传入的短信签名必须是在阿里大鱼“管理中心-短信签名管理”中的可用签名。如“阿里大鱼”已在短信签名管理中通过审核，则可传入”阿里大鱼“（传参时去掉引号）作为短信签名。短信效果示例：【阿里大鱼】欢迎使用阿里大鱼服务。
     * 
     * @param string $value 
     */
    public function setSmsFreeSignName($value) {
        $this->params['sms_free_sign_name'] = $value;
        return $this;
    }

    /**
     * [可选]设置短信模板变量，传参规则{"key":"value"}，key的名字须和申请模板中的变量名一致，多个变量之间以逗号隔开。示例：针对模板“验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！”，传参时需传入{"code":"1234","product":"alidayu"}
     * 
     * @param json $value 
     */
    public function setSmsParam($value) {
        $this->params['sms_param'] = $value;
        return $this;
    }

    /**
     * [必须]设置短信接收号码。支持单个或多个手机号码，传入号码为11位手机号码，不能加0或+86。群发短信需传入多个号码，以英文逗号分隔，一次调用最多传入200个号码。示例：18600000000,13911111111,13322222222
     * 
     * @param string $value 
     */
    public function setRecNum($value) {
        $this->params['rec_num'] = $value;
        return $this;
    }

    /**
     * [必须]设置短信模板ID，传入的模板必须是在阿里大鱼“管理中心-短信模板管理”中的可用模板。示例：SMS_585014
     * 
     * @param string $value 
     */
    public function setSmsTemplateCode($value) {
        $this->params['sms_template_code'] = $value;
        return $this;
    }

    /**
     * 返回所有参数
     * @return [type] [description]
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * 获取随机位数数字
     * @param  integer $len 长度
     * @return string       
     */
    protected static function randString($len = 6) {
        $chars = str_repeat('0123456789', $len);
        $chars = str_shuffle($chars);
        $str = substr($chars, 0, $len);
        return $str;
    }

    public function sendSms($smsTemplateCode, $recNum, $smsParam = array(), $smsFreeSignName, $smsType = 'normal', $extend = '') {

        // 设置请求参数
        $req = $this->setSmsTemplateCode($smsTemplateCode)
                ->setRecNum($recNum)
                ->setSmsParam(json_encode($smsParam))
                ->setSmsFreeSignName($smsFreeSignName)
                ->setSmsType('normal')
                ->setExtend($extend);

        print_r($this->execute($req));
    }

}
