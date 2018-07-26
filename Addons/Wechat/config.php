<?php
$ukey =  build_token_key(15);
return array(
	'group'=>array(
		'type'=>'group',
		'options'=>array(
			'basicsettings'=>array(
				'title'=>'基本设置',
				'options'=>array(
					'url'=>array(
						'title'=>'接口URL:',
						'type'=>'text',		 
						'value'=>U(C("DEFAULT_MODULE")."/Addons/execute@".$_SERVER['HTTP_HOST'], array("_addons"=>"Wechat","_controller"=>"Wechat","_action"=>"index","ukey"=>$ukey)),
						'tip'=>'请将此地址复制到<a href="https://mp.weixin.qq.com" target="_blank">微信公众平台</a>接口URL项'
					),
					'ukey'=>array(
						'title'=>'接口标识:',
						'type'=>'text',
						'value'=>$ukey,			 
					),
					'token'=>array(
						'title'=>'微信Token:',
						'type'=>'text',		 
						'value'=>build_token_key(),
						'tip'=>'请与<a href="https://mp.weixin.qq.com" target="_blank">微信公众平台</a>Token保持一致'
					),
                                        'encrypt_type'=>array(
						'title'=>'消息加解密方式:',
						'type'=>'select',
                                                'options'=>array(
							'express'=>'明文模式',
							'compatible'=>'兼容模式',
							'security'=>'安全模式',
						),
                                                'value'=>'express',
                                                'tip'=>'请与<a href="https://mp.weixin.qq.com" target="_blank">微信公众平台</a>encrypt_type保持一致'
					),
					'encodingAesKey'=>array(
						'title'=>'消息加密字符串:',
						'type'=>'text',
						'value'=>$encodingAesKey,
                                                'tip'=>'请将此字符串复制到<a href="https://mp.weixin.qq.com" target="_blank">微信公众平台</a>encodingAesKey参数'
					),
				)
			),
			'developer'=>array(
				'title'=>'高级设置',
				'options'=>array(
					'appid'=>array(
						'title'=>'AppId:',
						'type'=>'text',
						'value'=>'',
						'tip'=>'请与<a href="https://mp.weixin.qq.com" target="_blank">微信公众平台</a>开发者凭据AppId保持一致'
					),
					'appsecret'=>array(
						'title'=>'AppSecret:',
						'type'=>'text',
						'value'=>'',
						'tip'=>'请与<a href="https://mp.weixin.qq.com" target="_blank">微信公众平台</a>开发者凭据AppSecret保持一致'
					),
					'mchid'=>array(
						'title'=>'MCHID:',
						'type'=>'text',
						'value'=>'',
						'tip'=>'请与商户号MCHID保持一致'
					),
					'key'=>array(
						'title'=>'KEY:',
						'type'=>'text',
						'value'=>'',
						'tip'=>'请与商户支付密钥KEY保持一致'
					),
					'codelogin'=>array(
						'title'=>'是否使用微信二维码登陆:',
						'type'=>'radio',
						'options'=>array(
							'0'=>'关闭',
							'1'=>'开启'
							
						),
						'value'=>'0',
						'tip'=>'你的微信公众号必须有高级接口权限才能使用此功能'
					),
					'codeloginlocation'=>array(
						'title'=>'微信二维码登陆位置:',
						'type'=>'checkbox',
						'options'=>array(
							'index'=>'前台',
							'admin'=>'后台'
						),
						'tip'=>'此设置需开启二维码登陆才生效',
						'topkey'=>'codelogin',
						'topval'=>'1'
					)
				)
			),
			'msgset'=>array(
				'tip'=>'目前只支持文本类型回复',
				'title'=>'消息设置',
				'options'=>array(
					'default'=>array(
						'title'=>'默认回复消息',
						'type'=>'optiongroup',
						'options'=>array(
							'msgtype'=>array(
								'title'=>'回复格式',
								'type'=>'select',
								'options'=>array(
									'text'=>'文本',
									'image'=>'图片',
									'voice'=>'语音',
									'video'=>'视频',
									'music'=>'音乐',
									'news'=>'图文',
								),
								'value'=>'text'
							),
							'content'=>array(
								'title'=>'回复内容',
								'type'=>'text',
								'value'=>'您的消息我们已收到，我们会尽快答复您！感谢您对我们的支持！'
							)
						)
					),
					'subscribe'=>array(
						'title'=>'首次关注回复消息',
						'type'=>'optiongroup',
						'options'=>array(
							'msgtype'=>array(
								'title'=>'回复格式',
								'type'=>'select',
								'options'=>array(
									'text'=>'文本',
									'image'=>'图片',
									'voice'=>'语音',
									'video'=>'视频',
									'music'=>'音乐',
									'news'=>'图文',
								),
								'value'=>'text'
							),
							'content'=>array(
								'title'=>'回复内容',
								'type'=>'text',
								'value'=>'感谢您关注我们！'
							)
						)
					)
				)
			),
			'menu'=>array(
				'tip'=>'目前自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。<strong>请注意，创建自定义菜单后，由于微信客户端缓存，需要24小时微信客户端才会展现出来。</strong>',
				'title'=>'自定义菜单',
				'options'=>array(
					'button'=>array(
						'title'=>'菜单',
						'type'=>'dynamicgroup',
						'max'=>3,
						'options'=>array(
							'0'=>array(
								'name'=>'name',
								'title'=>'名称',
								'type'=>'text',
								'value'=>'',
								'tip'=>'',
								'width'=>'100px',
								'maxlength'=>4,
								'sub_button'=>array(
									'0'=>array(
										'type'=>array(
											'title'=>'菜单类型',
											'type'=>'select',
											'options'=>array(
												'click'=>'内部',
												'view'=>'外部',
											),
											'value'=>'click'
										),
										'name'=>array(
											'title'=>'名称',
											'type'=>'text',
											'value'=>'',
											'width'=>'100px',
											'maxlength'=>7,
										),
										'key'=>array(
											'title'=>'标识',
											'type'=>'text',
											'value'=>'',
											'width'=>'150px'
										),
										'url'=>array(
											'title'=>'链接',
											'type'=>'text',
											'value'=>'',
											'width'=>'150px'
										)
									)
								)
							)
						)
					)
				)
			)
		)
	)
);
/**
 * 生成Token
 */
function build_token_key($s = 30){
	$chars  = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$chars  = str_shuffle($chars);
	return substr($chars, 3, $s);
}