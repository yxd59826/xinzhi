<?php
/**
 * AdminCode
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/3/3
 * Time: 17:51
 */

namespace app\admin\controller;


use app\BaseController;
use think\Image;

class AdminCode extends BaseController
{
    /**
     * Notes   : 获取图片验证码
     * Author  : yxd
     * DateTime: 2021/3/4 10:57
     */
    public function getCode(){
        $one = rand(1,9);
        $two = rand(1,9);
        $data['img'] = self::createCode($one,$two);
        $data['uuid'] = 'code-key-6f1f139df18347b0bd61a779aef73e9f';
        return success($data);
    }

    /**
     * Notes   : 生成图片验证码
     * Author  : yxd
     * DateTime: 2021/3/4 10:58
     * @param $one
     * @param $two
     * @param string $prefix
     * @param int $font_size
     * @return string
     */
    public function createCode($one,$two,$prefix = '', $font_size = 32)
    {
        ob_get_clean();
        //创建真彩色白纸
        $width      = $font_size*5;
        $height      = $font_size+1;
        $im        = @imagecreatetruecolor($width, $height) or die("建立图像失败");
        //获取背景颜色
        $background_color = imagecolorallocate($im, 255, 255, 255);
        //填充背景颜色
        imagefill($im, 0, 0, $background_color);
        //逐行炫耀背景，全屏用1或0
//        for($i = 2;$i < $height - 2;$i++) {
//            //获取随机淡色
//            $line_color = imagecolorallocate($im, rand(240,255), rand(240,255), rand(240,255));
//            //画线
//            imageline($im, 2, $i, $width - 1, $i, $line_color);
//        }
        //设置印上去的文字
        $firstNum = $one;
        $secondNum = $two;
        $actionStr = $firstNum > $secondNum ? '-' : '+';
        //获取第1个随机文字
        $imstr[0]["s"] = $firstNum;
        $imstr[0]["x"] = rand(2, 5);
        $imstr[0]["y"] = rand(1, 4);
        //获取第2个随机文字
        $imstr[1]["s"] = $actionStr;
        $imstr[1]["x"] = $imstr[0]["x"] + $font_size - 1 + rand(0, 1);
        $imstr[1]["y"] = rand(1,5);
        //获取第3个随机文字
        $imstr[2]["s"] = $secondNum;
        $imstr[2]["x"] = $imstr[1]["x"] + $font_size - 1 + rand(0, 1);
        $imstr[2]["y"] = rand(1, 5);
        //获取第3个随机文字
        $imstr[3]["s"] = '=';
        $imstr[3]["x"] = $imstr[2]["x"] + $font_size - 1 + rand(0, 1);
        $imstr[3]["y"] = 3;
        //获取第3个随机文字
        $imstr[4]["s"] = '?';
        $imstr[4]["x"] = $imstr[3]["x"] + $font_size - 1 + rand(0, 1);
        $imstr[4]["y"] = 3;
        //文字
        $text = '';
        //写入随机字串
        $fontFile = app()->getRootPath().'public/static/fonts/Ac.ttf';
        for($i = 0; $i < 5; $i++) {
            //获取随机较深颜色
            $text_color = imagecolorallocate($im, rand(50, 180), rand(50, 180), rand(50, 180));
            $text .= $imstr[$i]["s"];
            //画文字
            imagechar($im, $font_size, $imstr[$i]["x"], $imstr[$i]["y"], $imstr[$i]["s"], $text_color);
            imagettftext($im,100,0,30,150,$text_color,$fontFile,$text);
        }
        session_start();
        $_SESSION[$prefix.'verifycode'] = $firstNum > $secondNum ? ($firstNum - $secondNum) : ($firstNum + $secondNum);
        //显示图片
        ob_start();
        ImagePng($im);
        $content = ob_get_clean();
        ImageDestroy($im);
        //销毁图片
        return 'data:image/png;base64,'. base64_encode($content);
    }

    /**
     * Notes   : 验证验证码是否正确
     * Author  : yxd
     * DateTime: 2021/3/4 10:58
     */
    public static function check($code,$prefix = '')
    {
        session_start();
        // var_dump($_SESSION['verifycode']);die;
        if(trim($_SESSION['verifycode']) == trim($code)) {
            echo 1;
            // return true;
        } else {
            echo 2;
            // return false;
        }
    }
}