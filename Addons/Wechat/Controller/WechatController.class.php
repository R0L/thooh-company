<?php

namespace Addons\Wechat\Controller;

use Home\Controller\AddonsController;
use Vendor\Wechat\Wechat;

class WechatController extends AddonsController {

    public function index() {
        $weObj = new Wechat();
        $weObj->valid();
        $type = $weObj->getRev()->getRevType();
        switch ($type) {
            case Wechat::MSGTYPE_TEXT:
                $weObj->text("hello, I'm wechat")->reply();
                exit;
                break;
            case Wechat::MSGTYPE_EVENT:
                break;
            case Wechat::MSGTYPE_IMAGE:
                break;
            default:
                $weObj->text("help info")->reply();
        }
    }
    
    
    
}
