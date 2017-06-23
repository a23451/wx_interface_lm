<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    function __construct(){
        parent::__construct();
        $filepath='access_token.txt';
        if (!isset($_SESSION['access_token'])||!isset($_SESSION['access_token_time'])) {
            if (file_exists($filepath)) {
                $str=file_get_contents($filepath);
                $arr=json_decode($str,true);
                $time=$arr['access_token_time'];
                $second=time()-$time;//echo $second."<hr>没有SESSION，有access_token文件<hr>";
                if ($second>7000) {
                    $access_token_array=$this->creat_token($filepath);/////////////access_token存入$_SESSION写入文件
                }else{
                    $_SESSION['access_token']=$arr['access_token'];
                    $_SESSION['access_token_time']=$arr['access_token_time'];
                }
            }else{
                $access_token_array=$this->creat_token($filepath);/////////////access_token存入$_SESSION写入文件
            }
        }else{
            $time=time()-$_SESSION['access_token_time'];
            if($time>7000){
                $access_token_array=$this->creat_token($filepath);/////////////access_token存入$_SESSION写入文件
            }
        }
            echo "<hr>access_token::".$_SESSION['access_token']."<hr>";
    }


/*创建access_token存入$_SESSION写入文件
$filepath 存access_token 的文件access_token.txt' 相对路径
*/
    public function creat_token($filepath='access_token.txt')
    {echo "<hr><hr><hr><hr><hr><hr><hr><hr>";
        $file=fopen($filepath,'w+');
        $time=time();
        $tokenClass=new Access_tokenController();
        $access_token=$tokenClass->get_access_token();
        $_SESSION['access_token']=$access_token;
        $_SESSION['access_token_time']=$time;
        $array = array('access_token' =>$access_token ,'access_token_time'=>$time );
        $arr=json_encode($array);
        fwrite($file,$arr);
        return $array;
    }


/*

*/
    public function index()
    {
        // if (isset($_GET['signature'])) {
        //     echo $_GET['echostr'];
        // }
        if($GLOBALS["HTTP_RAW_POST_DATA"]){
            $this->responseMsg();
        }
        
    }
/*
手动创建一次token
*/    
    public function test_token()
    {
        $this->creat_token();
    }
/*
test
*/    
    public function test_test()
    {
        $this->show("hello");
    }
/*
test
也以科技测试号appID
wxa999c5a9f7b1519b
*/    
    public function test_clear_quota()
    {
        $data='{"appid":"wxa999c5a9f7b1519b"}';
        $access_token=$_SESSION['access_token'];
        $url='https://api.weixin.qq.com/cgi-bin/clear_quota?access_token='.$access_token;
        $material=new MaterialController();
        $result=$material->lm_curl_post($data,$url);
        var_dump($result);
    }

    public function responseMsg()  
    {  
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];    
  
        if (!empty($postStr)){    
            libxml_disable_entity_loader(true);//安全防护    
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);    
            $fromUsername = $postObj->FromUserName;    
            $toUsername = $postObj->ToUserName;  
            $createTime = $postObj->CreateTime;  
            $msgType = $postObj->MsgType;  
            $content = $postObj->Content;  
            $msgId = $postObj->MsgId;  
            if($msgType == 'text'){  
                $textTpl = "<xml>    
                        <ToUserName><![CDATA[%s]]></ToUserName>    
                        <FromUserName><![CDATA[%s]]></FromUserName>    
                        <CreateTime>%s</CreateTime>    
                        <MsgType><![CDATA[text]]></MsgType>    
                        <Content><![CDATA[%s]]></Content>    
                        <FuncFlag>0</FuncFlag>  
                        </xml>";  
                $time = time();  
                //$contentStr = "您发的是消息包含以下信息：\n发信人OpenID：".$fromUsername."\n收信人微信号：".$toUsername."\n发信时间：".$createTime."\n消息类型：".$msgType."\n消息内容：".$content."\n消息ID：".$msgId;    
                 $contentStr="您好，云牛为您服务";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);    
                echo $resultStr;    
            }else{  
                $textTpl = "<xml>    
                        <ToUserName><![CDATA[%s]]></ToUserName>    
                        <FromUserName><![CDATA[%s]]></FromUserName>    
                        <CreateTime>%s</CreateTime>    
                        <MsgType><![CDATA[text]]></MsgType>    
                        <Content><![CDATA[%s]]></Content>    
                        <FuncFlag>0</FuncFlag> 
                        </xml>";  
                $time = time();  
                $contentStr = "您发的消息类型不是文本。而是".$msgType;  
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);    
                echo $resultStr;    
            }  
  
        }  
    } 








////////////////////////////////////////////////

//自动发送临时图文消息接口
    public function test7($getData='')
    {
        if (!$getData) {
            $getData=$_POST;
        }        
        $getNews=array();
        //foreach逐条处理
        foreach ($getData as $key => $value) {
            $theNews=json_decode($value,true);            
            $theNews['content']=$this->test8($theNews['content']);//OK//自动上传处理图文中的图片            
            if ($theNews['content']==11) {
                echo "error:11,图文中的图片错误";return 11;
            }else{
                echo "图文中的图片OK";
            }
            if (isset($theNews['thumb'])) {

                $theNews['thumb']=$this->test9($theNews['thumb'],$type='thumb');//OK//自动上传处理缩略图获取thumb_id
                if ($theNews['thumb']==22) {
                    echo "error:22,缩略图错误";return 22;
                }else{
                    echo "缩略图OK<hr>";
                }
            }else{$theNews['thumb']='https://limingliming.xyz/wx/10.jpg';}
            $getNews[]=$theNews;
        }
        //var_dump($getNews);///////////////POST数据处理完毕，可以进行拼接

        $data=$this->test11($getNews);//拼接图文消息data
  
        var_dump($data);echo "<hr>";
       
        $media_id=$this->test12($data);//获得图文消息meida_id
        if ($media_id==44) {
            echo "error:44 获得图文消息meida_id错误";return 44;
        }
        else{            
            echo "图文消息meida_id".$media_id;
        }
        return $media_id;//返回图文消息meida_id

        //$info=$this->test13($media_id);//预览图文消息

    }


















    public function test8($content)//自动上传处理图文中的图片test7和test27调用，临时、永久图文消息中图片获取URL，接口一样
    {
    
        $content=$content;
        $pattern='/(<img)(.*?)(src=")(.*?)"/m';
        $matches=preg_match_all($pattern, $content,$pert_arr);//$pert_arr[4]也是个数组,指正则匹配到所有的目标中，每个目标的第四个括号(.*?)部分。

        //正则匹配图文消息中的图片src，得到一个src数组，foreach(上传图文消息中的图片得到url,再正则匹配图文消息中的图片src一次，用URL替换）

        foreach ($pert_arr[4] as $key => $value) {
            
            $cla=new UploadTolocalController();
            $data=$cla->lm_download_img($value);//下载至本地服务器返回本地文件地址
            $mid=new MediaController();
            $access_token=$_SESSION['access_token'];
            $result=$mid->lm_media_uploadimg($data,$access_token);//上传至微信服务器返回本URL
            //var_dump($result);
            //$result='http://mmbiz.qpic.cn/mmbiz_png/TIrfeWxde8GchduWV6M1RibJKl4eqGAep1l5MGsGKTrjVCiaUtPDI1b9HrHxfV5SaB0MRic47hMkia32nSTTiby3xYQ/0';
            if ($result==11) {
                return 11;
                echo "error:11";
            }
            $content=str_replace($value,$result,$content);
        }
        $content=str_replace('"','\"',$content);
        return $content;

    }


    public function test11($getNews)//拼接图文消息data，调用media类里上传图文消息方法
    {
        $str1 = '{"articles":[';
        $strArr=array();
        foreach ($getNews as $key => $value) {
               $strArr[]='{
                                "thumb_media_id":"'.$value['thumb'].'",
                                "author":"'.$value['author'].'",
                     "title":"'.$value['title'].'",
                     "content_source_url":"https://limingliming.xyz",
                     "content":"'.$value['content'].'",
                     "digest":"",
                                "show_cover_pic":0
                 }';
           }   

        $str2=implode(",", $strArr);
        $str3=']}';
        $data=$str1.$str2.$str3;
        return $data;
/*
        $data = '{
           "articles": [
                 {
                                "thumb_media_id":"'.$thumb_media_id.'",
                                "author":"'.$author.'",
                     "title":"Happy Day1",
                     "content_source_url":"www.qq.com",
                     "content":"'.$content.'",
                     "digest":"",
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
*/

    }


/*
test9自动上传处理缩略图由test7调用
$thumb 图片等的网络地址http://url/img.jpg,http://url/mp3.mp3
$type由函数里lm_media_upload()决定可以是image，voice，video，thumb
如果是$type='thumb'返回thumb_media_id
 */
    public function test9($thumb,$type='thumb')//自动上传处理缩略图由test7调用
    {
        $cla=new UploadTolocalController();
        $data=$cla->lm_download_img($thumb);//下载至本地服务器返回本地文件地址数组形式
        //var_dump($data);return;
        $cla=new MediaController();
        $access_token=$_SESSION['access_token'];
        $result=$cla->lm_media_upload($data,$access_token,$type);//上传至微信服务器返回本缩略图media_id
        //$result='Nj_aihfges3ZVCIL6Rn0uNJTLPieorp0gLgA8RjKv5H07sRL1olvpLTrXuo434q2';
        return $result;
    }

/*
test12上传图文消息返回临时图文消息id
$data上传图文消息用到的data
*/
    public function test12($data)
    {
        $access_token=$_SESSION['access_token'];
        $cla=new MediaController();
        $media_id=$cla->lm_media_uploadnews($data,$access_token);
        if ($media==44) {
            return 44;
        }
        return $media_id;
    }

/*
test13预览图文消息
$media_id图文消息media_id
*/
    public function test13($media_id)
    {
        $access_token=$_SESSION['access_token'];
        $cla=new SendallController();
        $info=$cla->lm_mass_preview($media_id,$access_token);
        return $info;
    }

/*
test13预览图文消息
test23发送图文消息
$media_id图文消息media_id
*/
    public function test23($media_id)
    {
        $access_token=$_SESSION['access_token'];
        $cla=new SendallController();
        $inof=$cla->lm_mass_sendall($media_id,$access_token);
        return $info;
    }





////////////////////////////////////////////////

//自动发送临时图文消息接口
//预览
    public function media_preview()
    {
        $media_id=$this->test7();
        //$media_id='jZzZSBLlQlk1v5Yx7G11f4dey82ki2Nl79Jpl15vfHyrcLKtZKf1o84L8S15Mfbg';//临时
        //$media_id='lJiDlt_d5HY-N7rJMKYNf2depd9niwhksoabGJ55qm4';//永久
        //$media_id='lJiDlt_d5HY-N7rJMKYNf4PLozCsLkqnqodhGqH0Dgc';//永久
        //$media_id='3psCcGYfjCeM30bN2C0cTIlqyBa4N9PaeWZeb_WhZHZlnsZSz7m6eCDh7qTrgizd';//临时
        $info=$this->test13($media_id);//预览图文消息
         var_dump($info);
         return $info;
    }

//自动发送永久图文消息接口


//预览
    public function preview()
    {
        $media_id=$this->test27();
        $_SESSION['news_media_id']=$media_id;
        //$media_id='jZzZSBLlQlk1v5Yx7G11f4dey82ki2Nl79Jpl15vfHyrcLKtZKf1o84L8S15Mfbg';//临时
        //$media_id='lJiDlt_d5HY-N7rJMKYNf2depd9niwhksoabGJ55qm4';//永久
        //$media_id='lJiDlt_d5HY-N7rJMKYNf4PLozCsLkqnqodhGqH0Dgc';//永久
        //$media_id='3-VSn00hQFFNTts-MqI_ahsOjzqRq0n9Qs6y1FsV66M';//永久云牛
        $info=$this->test13($media_id);//预览图文消息
         var_dump($info);
    }
//群发
    public function sendall()
    {
        if ($_SESSION['news_media_id']) {
            $media_id=$_SESSION['news_media_id'];
        }else{
            $media_id=$this->test27();
        }
        if ($info==11||$info==22||$info==44||!$media_id) {echo "error";  return false;  }
        //$media_id='nZiDPQGvH7JvfnHXMaMRpPBIdTIgfs34R9ZTEIh-E1Zy6BqYafxEw2TUXwdajvKc';//临时
        $info=$this->test23($media_id);//群发图文消息    
        var_dump($info);    
    }
/*
返回示例
图文消息meida_id8r779g-TcWsPTRe2QELns3SGKwuB1pwQGKaLK-B5V50array(4) { ["errcode"]=> int(0) ["errmsg"]=> string(27) "send job submission success" ["msg_id"]=> int(1000000002) ["msg_data_id"]=> float(2454358828) } NULL "
 */



/*
test27自动发送永久图文消息接口,接受处理消息
 */
    public function test27($getData='')
    {
/*        $data = '{
   "articles": [
         {
                        "thumb_media_id":"Nj_aihfges3ZVCIL6Rn0uNJTLPieorp0gLgA8RjKv5H07sRL1olvpLTrXuo434q2",
                        "author":"xxx",
             "title":"Happy Day",
             "content_source_url":"https:limingliming.xyz",
             "content":"<img class=\"alignnone size-full wp-image-80\" src=\"http://mmbiz.qpic.cn/mmbiz_png/TIrfeWxde8GchduWV6M1RibJKl4eqGAep1l5MGsGKTrjVCiaUtPDI1b9HrHxfV5SaB0MRic47hMkia32nSTTiby3xYQ/0\" alt=\"\" width=\"212\" height=\"236\" />",
             "digest":"digest",
                        "show_cover_pic":1
         },
         {
                        "thumb_media_id":"Nj_aihfges3ZVCIL6Rn0uNJTLPieorp0gLgA8RjKv5H07sRL1olvpLTrXuo434q2",
                        "author":"xxx",
             "title":"Happy Day",
             "content_source_url":"https:limingliming.xyz",
             "content":"<img class=\"alignnone size-full wp-image-80\" src=\"http://mmbiz.qpic.cn/mmbiz_png/TIrfeWxde8GchduWV6M1RibJKl4eqGAep1l5MGsGKTrjVCiaUtPDI1b9HrHxfV5SaB0MRic47hMkia32nSTTiby3xYQ/0\" alt=\"\" width=\"212\" height=\"236\" />",
             "digest":"digest",
                        "show_cover_pic":1
         }
   ]
}';
*/
        if (!$getData) {
            $getData=$_POST;
        }   
        $getNews=array();
        //foreach逐条处理
        foreach ($getData as $key => $value) {
            $theNews=json_decode($value,true);
            //$theNews['content']='管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人<img src="http://192.168.152.1/wordpress/wp-content/uploads/2017/05/KWLQOQZ53IMM_WHDZ2P4H9-150x150.png" alt="" width="212" height="236" class="alignnone size-full wp-image-80" />管理联系人管理联系人管理联系人<img src="http://192.168.152.1/wordpress/wp-content/uploads/2017/05/timg-1-300x250.jpg" alt="" width="212" height="236" class="alignnone size-full wp-image-80" />管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人管理联系人';
            $theNews['content']=$this->test8($theNews['content']);//OK//自动上传处理图文中的图片            
            if ($theNews['content']==11) {
                echo "error:11,图文中的图片错误";return 11;
            }else{
                echo "图文中的图片OK";
            }
            if (isset($theNews['thumb'])) {

                $theNews['thumb']=$this->test29($theNews['thumb'],$type='thumb');//OK//自动上传处理缩略图获取thumb_id
                if ($theNews['thumb']==22) {
                    echo "error:22,缩略图错误";return 22;
                }else{
                    echo "缩略图OK<hr>";
                }
            }
            $getNews[]=$theNews;
        }
        //var_dump($getNews);///////////////POST数据处理完毕，可以进行拼接

        $data=$this->test11($getNews);//拼接图文消息data
  
        var_dump($data);echo "<hr>";
       
        $media_id=$this->test22($data);//获得图文消息meida_id
        if ($media_id==44) {
            echo "error:44 获得图文消息meida_id错误";return 44;
        }
        else{            
            echo "图文消息meida_id".$media_id;
        }
       //$media_id='lJiDlt_d5HY-N7rJMKYNf_LWrdt9X7uBvfLZ4q52xfY';永久
       //$media_id='lJiDlt_d5HY-N7rJMKYNf2depd9niwhksoabGJ55qm4';//永久
        return $media_id;//返回图文消息meida_id

        //$info=$this->test13($media_id);//预览图文消息



    }


    /*
test29自动上传处理缩略图由test7调用
$thumb 图片等的网络地址http://url/img.jpg,http://url/mp3.mp3
$type由函数里lm_material_upload()决定可以是image，voice，video，thumb
返回永久素材id   media_id
 */
    public function test39($thumb,$type='thumb')//自动上传处理缩略图由test7调用
    {
        $data = array("file"=>"@d:\\logo.jpg");
        $cla=new MaterialController();
        $access_token=$_SESSION['access_token'];
        $result=$cla->lm_material_upload($data,$access_token,$type);//

    }
    public function test29($thumb,$type='thumb')//自动上传处理缩略图由test7调用
    {
        $cla=new UploadTolocalController();
        $data=$cla->lm_download_img($thumb);//下载至本地服务器返回本地文件地址数组形式
        $cla=new MaterialController();
        $access_token=$_SESSION['access_token'];
        $result=$cla->lm_material_upload($data,$access_token,$type);//上传至微信服务器返回本缩略图media_id
        return $result;
    }

/*
test22上传永久图文消息返回图文消息id
$data上传永久图文消息用到的data
*/
    public function test22($data)
    {
        $access_token=$_SESSION['access_token'];
        $cla=new MaterialController();
        $media_id=$cla->lm_material_uploadnews($data,$access_token);
        if ($media==44) {
            return 44;
        }
        return $media_id;
    }







}
