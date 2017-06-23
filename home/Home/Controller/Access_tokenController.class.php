<?php
namespace Home\Controller;
use Think\Controller;
class Access_tokenController extends Controller {

    //获取access token 的方法
    public function get_access_token()
    {
    
 
/*
         //云牛测试号   登陆云牛测试号要用别人的微信扫描
        $appid = "wx83c7480c401bac65";
        $appsecret = "f888904f212f9200b31d6b3c1b59dfea";
*/
       
         //云牛通信号
        $appid = "wxaccaa842dea64e8f";
        $appsecret = "0a721bb078fb68c6579d791c53928242";

/*                  
         //也以科技测试号
        $appid = "wxa999c5a9f7b1519b";
        $appsecret = "e34ac8be28a55a9c0238a9e0733ca02a";
        
          
        
        
         //也以科技公众号       
        $appid = "wx61c38f4061b7faff";
        $appsecret = "fd2dda2d88412b146972f4d0b52cfe09";
         
   */   

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $jsoninfo = json_decode($output, true);
        return $jsoninfo["access_token"];
    }




}