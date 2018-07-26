<?php

return array(
    'type' => array(
        'title' => '图片变换方式:',
        'type' => 'select',
        'options' => array(
            'baisc' => '基本',
            'word' => '图文混排',
        ),
        'value' => 'baisc',
    ),
    'animation' => array(
        'title' => '图片变换方式:',
        'type' => 'select',
        'options' => array(
            'fade' => '淡入淡出',
            'slide' => '滑动',
        ),
        'value' => 'fade',
    ),
    'slideshowSpeed' => array(
        'title' => '轮播间隔时间（单位 毫秒）:',
        'type' => 'text',
        'value' => '3000' //表单的默认值
    ),
    'imgWidth' => array(
        'title' => '容器宽度（单位　像素）',
        'type' => 'text',
        'value' => '400'
    ),
    'imgHeight' => array(
        'title' => '容器高度（单位　像素）',
        'type' => 'text',
        'value' => '300'
    ),
    'direction' => array(
        'title' => '图片滚动方向:',
        'type' => 'radio',
        'options' => array(
            'horizontal' => '横向滚动',
            'vertical' => '纵向滚动',
        ),
        'value' => 'horizontal',
    ),
    'pausePlay' => array(
        'title' => '是否显示播放暂停按钮:',
        'type' => 'radio',
        'options' => array(
            '0' => '否',
            '1' => '是',
        ),
        'value' => '0',
    ),
    'directionNav' => array(
        'title' => '是否显示左右控制按钮:',
        'type' => 'radio',
        'options' => array(
            '0' => '否',
            '1' => '是',
        ),
        'value' => '0',
    ),
    'animationLoop' => array(
        'title' => '是否循环滚动 循环播放:',
        'type' => 'radio',
        'options' => array(
             '0' => '否',
             '1' => '是',
         ),
        'value' => '1',
    ),
    'pauseOnHover' => array(
        'title' => '鼠标糊上去是否暂停:',
        'type' => 'radio',
        'options' => array(
            '0' => '否',
            '1' => '是',
        ),
        'value' => '1',
    ),
    'images' => array(
        'title' => '轮播图片（双击可移除）',
        'type' => 'picture_union',
        'value' => ''
    ),
    'url' => array(
        'title' => '图片链接|图片文字|top|right（一行对应一个图片）',
        'type' => 'textarea',
        'value' => ''
    ),
);
