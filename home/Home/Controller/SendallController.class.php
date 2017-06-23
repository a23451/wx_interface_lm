<?php
namespace Home\Controller;
use Think\Controller;
/*
该类没有构造函数来确定access_token
 */
class SendallController extends Controller {

    public function lm_curl_post($data,$url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
          var_dump(curl_error($ch));
          return curl_error($ch);
        }
        curl_close($ch);
        return $tmpInfo;
    }




    //////////////////


    /*lm_mass_preview预览接口【订阅号与服务号认证后均可用】
    **
    */
//"touser":"oyzvBtyObdaQb9jLMxDNdvTWIRCI", //云牛通信公众号 cc 我的测试openid
//"touser":"oB2KbwynfMIy-b1WTxwdfiEcZcu0", //也以科技测试号 cc 我的测试openid
    public function lm_mass_preview($media_id,$access_token)
    {
        $data='{
                   "touser":"oyzvBtyObdaQb9jLMxDNdvTWIRCI", 
                   "mpnews":{              
                            "media_id":"'.$media_id.'"               
                             },
                   "msgtype":"mpnews" 
                }';
        $url="https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=".$access_token;
        $tmpInfo=$this->lm_curl_post($data,$url);
        $tmpInfo=json_decode($tmpInfo,true);//true 转换成数组，false 转换成object
        return $tmpInfo;
    }
/*
$data='{
   "touser":"OPENID", 
   "mpnews":{              
            "media_id":"123dsdajkasd231jhksad"               
             },
   "msgtype":"mpnews" 
}';


*/
//////////////////


    //////////////////


    /*lm_mass_sendall群发接口【订阅号与服务号认证后均可用】
    **
    */

    public function lm_mass_sendall($media_id,$access_token)
    {
        $data='{
                   "filter":{
                      "is_to_all":true,
                      "tag_id":101
                   },
                   "mpnews":{
                      "media_id":"'.$media_id.'"
                   },
                    "msgtype":"mpnews",
                    "send_ignore_reprint":0,
                    "clientmsgid":""
                }';
        $url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=".$access_token;
        $tmpInfo=$this->lm_curl_post($data,$url);
        $tmpInfo=json_decode($tmpInfo,true);//true 转换成数组，false 转换成object
        var_dump($tmpInfo);return $tmpInfo;
    }
/*
$data='{
   "filter":{
      "is_to_all":true,
      "tag_id":1
   },
   "mpnews":{
      "media_id":"123dsdajkasd231jhksad"
   },
    "msgtype":"mpnews",
    "send_ignore_reprint":0
}';


*/
//////////////////
}