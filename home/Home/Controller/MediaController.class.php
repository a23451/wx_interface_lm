<?php
namespace Home\Controller;
use Think\Controller;
//上传临时素材
class MediaController extends Controller {

    
    /*public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }*/

    public function tsett(){
        echo "media";
    }


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
          return curl_error($ch);
        }
        curl_close($ch);
        return $tmpInfo;
    }




    /*
    **
    lm_media_uploadimg上传图文消息内的图片获取URL【订阅号与服务号认证后均可用】
    $data = array("file"=>"@d:\\logo.jpg");
    file 键名随便写
    */
    public function lm_media_uploadimg($data,$access_token){
        $url="https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".$access_token;
        $tmpInfo=$this->lm_curl_post($data,$url);
        $tmpInfo=json_decode($tmpInfo,true);//true 转换成数组，false 转换成object
        if (isset($tmpInfo['url'])) {
            return $tmpInfo['url'];
        }else{
            var_dump($tmpInfo);
            return 11;
        }
        

    }



    /*
    **
    lm_media_uploadnews上传图文消息素材【订阅号与服务号认证后均可用】
    $data
    成功服务器返回 {"type":"news","media_id":"xZxCNhekNrC2kZ95ETK0nTkY8eieVR1SVfKq0NPDA2SpAk4yaYCTFBun4S1-CrgP","created_at":1496986178}
    return array['media_id']图文消息素材id
    $data函数后面有示例
    */
    public function lm_media_uploadnews($data,$access_token){
        $url="https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=".$access_token;
        $tmpInfo=$this->lm_curl_post($data,$url);
        $tmpInfo=json_decode($tmpInfo,true);//true 转换成数组，false 转换成object
        if (isset($tmpInfo['media_id'])) {
            return $tmpInfo['media_id'];
        }else{
            echo "<hr>access_token::".$access_token."<hr>";
            echo "ERROR:44,error info->";var_dump($tmpInfo);echo "<br>ERROR:44,stop here:".__FILE__."->";echo __FUNCTION__."<br>";
            return 44;
        }
        
    }
    /**
     *lm_media_uploadnews中参数 $data
    $data = '{
       "articles": [
             {
                            "thumb_media_id":"qI6_Ze_6PtV7svjolgs-rN6stStuHIjs9_DidOHaj0Q-mwvBelOXCFZiq2OsIU-p",
                            "author":"xxx",
                 "title":"Happy Day",
                 "content_source_url":"www.qq.com",
                 "content":"content",
                 "digest":"digest",
                            "show_cover_pic":1
             },
             {
                            "thumb_media_id":"qI6_Ze_6PtV7svjolgs-rN6stStuHIjs9_DidOHaj0Q-mwvBelOXCFZiq2OsIU-p",
                            "author":"xxx",
                 "title":"Happy Day",
                 "content_source_url":"www.qq.com",
                 "content":"content",
                 "digest":"digest",
                            "show_cover_pic":0
             }
       ]
    }';
     *
     *
     * 
     */


    /////////////////////////////////////


    /*
    **
    lm_media_upload 上传临时素材获取media_id
    $type
        图片（image）: 2M，支持PNG\JPEG\JPG\GIF格式
        语音（voice）：2M，播放长度不超过60s，支持AMR\MP3格式
        视频（video）：10MB，支持MP4格式
        缩略图（thumb）：64KB，支持JPG格式
    $data = array("file"=>"@d:\\logo.jpg");
    file 键名随便写  \\第一个是转义符
    return json数据转化后的数组
    {"type":"image","media_id":"FUN95cIMzTjKvwH-fJ7QzSLdFAkXLI1xnfpAwfCmHZwVt_RN6gX_ykqCxn5LdYPU","created_at":1496981705}
    */
    public function lm_media_upload($data,$access_token,$type){
        $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=".$type;
        $tmpInfo=$this->lm_curl_post($data,$url);
        $tmpInfo=json_decode($tmpInfo,true);
        if (isset($tmpInfo['thumb_media_id'])) {
            return $tmpInfo['thumb_media_id'];
        }else{
            return 22;
        }
        
    }

    //////////////////

    // $access_token="pjgg_aB_axp6XHZU2h6hll3JySOgljWUsFAgsnJUQu41o0LkA-uM0ulVmCd4K2LTXgV6sLEuiQOChJ0RHCXby7w1ICSIhi9h2ZmC8AYLO48OShpcVh-j-W76wm8Sf9FHFSBgABALYH";

    /*
         $type="thumb";
         $data = array("file"=>"@d:\\1.jpg");
         //print_r(lm_media_upload($data,$access_token,$type));
         print_r(lm_media_uploadimg($data,$access_token));

    */

    /*
        获得的缩略图id
         Array ( [type] => thumb [thumb_media_id] => 7Wy9JJgNuXj7fAVbH0ojCNt0oxJmNocenflqGM_3RbpoJhZLQGsOKfiS8Q9NVQy6 [created_at] => 1497156980 )
         
         Array ( [type] => thumb [thumb_media_id] => JUL2qxs8iufxmaYWwz3l1wsX8cpmG8ZNzImkGqswsjQ6AgrqfN_lY_f93g5BPrTd [created_at] => 1497159729 )
           
        获得的图文信息中的图片链接   
         {"url":"http:\/\/mmbiz.qpic.cn\/mmbiz_png\/TIrfeWxde8EqOwzPuQiaGd3FyafksEzps2nQ0BEuxk7QzzRiaiahU15C1XTtQQgibFAicVHibj1liaYDjAb2H2CETBEDA\/0"}
         
         {"url":"http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/TIrfeWxde8EqOwzPuQiaGd3FyafksEzpssicLQ1B3qRN1hVluDwHwiaFy0T4epx87d2wLibo9skfneBkUjsq0P8qwQ\/0"}
         
        获得的图文信息id 
         Array ( [type] => news [media_id] => XDoKtnHMjeIrM-8BLjrtoaTPiTgd44bXc1jLQAMg53hiawj1FBfUcZOroDkD6sgi [created_at] => 1497159006 )


        Array ( [type] => news [media_id] => NweXcYOHFp-nl7nFIp0aVTL8AmQvJL6DsJOoYcA-iKXQJ4Fu-Y_RbfhiCg_kfSEC [created_at] => 1497160400 )


        Array ( [type] => news [media_id] => jrY-si_skVR2Aka97DE8Ii7iy_ZH01tJzdCPmxT8KAcDvYtSGNVdg-Tvu9k_38Lj [created_at] => 1497160880 )

    */
   
    /*
    $content='<img src=\"http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/TIrfeWxde8EqOwzPuQiaGd3FyafksEzpssicLQ1B3qRN1hVluDwHwiaFy0T4epx87d2wLibo9skfneBkUjsq0P8qwQ\/0\" alt=\"image\">';//图文信息中的图片链接 ，替换content中原链接地址  

    $data = '{
       "articles": [
             {
                            "thumb_media_id":"7Wy9JJgNuXj7fAVbH0ojCNt0oxJmNocenflqGM_3RbpoJhZLQGsOKfiS8Q9NVQy6",
                            "author":"xxx",
                 "title":"Happy Day1",
                 "content_source_url":"www.qq.com",
                 "content":"content66666666666666666666666666'.$content.'",
                 "digest":"digest",
                            "show_cover_pic":1
             },
             {
                            "thumb_media_id":"JUL2qxs8iufxmaYWwz3l1wsX8cpmG8ZNzImkGqswsjQ6AgrqfN_lY_f93g5BPrTd",
                            "author":"xxx",
                 "title":"Happy Day2",
                 "content_source_url":"www.qq.com",
                 "content":"content66666666666666666666666666'.$content.'",
                 "digest":"digest",
                            "show_cover_pic":1
             },
             {
                            "thumb_media_id":"JQVyP1icBFiKW-KQ29splbL16pXwmbeXuWCPeJod41Me1dPFTUq2Zjg9wRqx0HtI",
                            "author":"xxx",
                 "title":"Happy Day3",
                 "content_source_url":"www.qq.com",
                 "content":"content",
                 "digest":"digest",
                            "show_cover_pic":1
             }
       ]
    }';

    print_r(lm_media_uploadnews($data,$access_token));

    */








}