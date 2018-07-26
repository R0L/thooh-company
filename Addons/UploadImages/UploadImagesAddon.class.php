<?php

namespace Addons\UploadImages;
use Common\Controller\Addon;

/**
 * 图片批量上传插件
 * @author tjr&jj
 */

    class UploadImagesAddon extends Addon{

        public $info = array(
            'name'=>'UploadImages',
            'title'=>'图片批量上传',
            'description'=>'图片的批量上传',
            'status'=>1,
            'author'=>'brighttj',
            'version'=>'0.3'
        );

        public function install(){
            //添加钩子
            $Hooks = M("Hooks");
            $WechatHooksList = array(array(
                    'name' => 'uploadImages',
                    'description' => '多图片上传的钩子',
                    'type' => 1,
                    'update_time' => NOW_TIME,
                    'addons' => 'UploadImages'
            ));
            $Hooks->addAll($WechatHooksList,array(),true);
            if ( $Hooks->getDbError() ) {
                    session('addons_install_error',$Hooks->getError());
                    return false;
            }
            return true;          
        }

        public function uninstall(){
            $Hooks = M("Hooks");
            $map['name']  = "uploadImages";
            $find = $Hooks->where($map)->find();
            if($find){
                $res = $Hooks->where($map)->delete();
                if($res == false){
                        session('addons_install_error',$Hooks->getError());
                        return false;
                }
            }
            return true;
        }

        //实现的uploadImages钩子方法
        public function uploadImages($data){
            $this->assign('addons_data', $data);
            $this->display('upload');
        }
    }