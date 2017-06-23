<?php
namespace Home\Controller;
use Think\Controller;
//上传永久素材
class MaterialController extends Controller {

    
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
    **这个接口跟临时素材接口一样
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
    成功服务器返回 {"media_id":MEDIA_ID}
    return array['media_id']图文消息素材id
    $data函数后面有示例
    */
    public function lm_material_uploadnews($data,$access_token){
        $url="https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=".$access_token;
        $tmpInfo=$this->lm_curl_post($data,$url);
        $tmpInfo=json_decode($tmpInfo,true);//true 转换成数组，false 转换成object
        if (isset($tmpInfo['media_id'])) {
            return $tmpInfo['media_id'];
        }else{
            echo "ERROR:44,error info->";var_dump($tmpInfo);echo "<br>ERROR:44,stop here:".__FILE__."->";echo __FUNCTION__."<br>";
            return 44;
        }
        
    }
    /**
     *lm_material_uploadnews中参数 $data
    $data = '{
       "articles": [
             {
                            "thumb_media_id":"qI6_Ze_6PtV7svjolgs-rN6stStuHIjs9_DidOHaj0Q-mwvBelOXCFZiq2OsIU-p",//图文消息的封面图片素材id（必须是永久mediaID）
                            "author":"xxx",
                 "title":"Happy Day",
                 "content_source_url":"www.qq.com",
                 "content":"content",
                 "digest":"digest",
                            "show_cover_pic":1
             },
             {
                            "thumb_media_id":"qI6_Ze_6PtV7svjolgs-rN6stStuHIjs9_DidOHaj0Q-mwvBelOXCFZiq2OsIU-p",//图文消息的封面图片素材id（必须是永久mediaID）
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
    lm_material_upload 上传永久素材获取media_id
    $type
        图片（image）: 2M，支持PNG\JPEG\JPG\GIF格式
        语音（voice）：2M，播放长度不超过60s，支持AMR\MP3格式
        视频（video）：10MB，支持MP4格式
        缩略图（thumb）：64KB，支持JPG格式
    $data = array("file"=>"@d:\\logo.jpg");
    file 键名随便写  \\第一个是转义符
    return json数据转化后的数组
    {"media_id":MEDIA_ID,"url":URL}
    */
    public function lm_material_upload($data,$access_token,$type){
        $data_media=array();
        $data_media['media']=$data['file'];
        $url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$access_token."&type=".$type;
        $tmpInfo=$this->lm_curl_post($data_media,$url);
        $tmpInfo=json_decode($tmpInfo,true);
        if (isset($tmpInfo['media_id'])) {
            return $tmpInfo['media_id'];
        }else{
            echo "ERROR:22,error info->";var_dump($tmpInfo);echo "<br>ERROR:22,stop here:".__FILE__."->";echo __FUNCTION__."<br>";
            return 22;
        }
        
    }

/*
array{ ["media_id"]=> string(43) "lJiDlt_d5HY-N7rJMKYNfxCy0K7mQu-LLq18I145Qe4" ["url"]=> string(136) "http://mmbiz.qpic.cn/mmbiz_png/TIrfeWxde8FdJqGrdDx6Oryiaoc2ibnxGF6dPW0jxZZTN0ibX9l3Kt1TKWicAI4rnaia8OADJ84GtuDOjoiadFlw05Gw/0?wx_fmt=png" } 
 */

    //////////////////

    /////////////////////////////////////


    /*
    **
    lm_batchget_material 获取素材列表
    $type 必须     素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
    $offset  必须   从全部素材的该偏移位置开始返回，0表示从第一个素材 返回
    $count 返回素材的数量，取值在1到20之间
    微信接口返回说明：永久图文消息素材列表，其他类型（图片、语音、视频）
    return json数据转化后的数组
    */
    public function lm_batchget_material($type,$offset=0,$count=20){
        $data='{"type":'.$type.',"offset":'.$offset.',"count":'.$count.'}';
        $url="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$access_token;
        $Info=$this->lm_curl_post($data_media,$url);
        $Info=json_decode($tmpInfo,true);
        return $Info;
    }

/*


其他类型（图片、语音、视频）的返回如下：
{
  "total_count": TOTAL_COUNT,
  "item_count": ITEM_COUNT,
  "item": [{
      "media_id": MEDIA_ID,
      "name": NAME,
      "update_time": UPDATE_TIME,
      "url":URL
  },
  //可能会有多个素材
  ]
}
*/    

}