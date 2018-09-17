<?php
    return [
      //后台登录验证码配置参数
      'captcha'  => [        // 验证码字符集合3.
          'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',

          'fontSize' => 22,         // 验证码字体大小(px)
          'useCurve' => false,      // 是否画混淆曲线
          'imageH'   => 50,         // 验证码图片高度
          'imageW'   => 150,        // 验证码图片宽度
          'length'   => 1,          // 验证码位数
          'reset'    => true        // 验证成功后是否重置
          ],
    ];
 ?>
