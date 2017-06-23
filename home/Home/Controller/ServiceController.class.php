<?php 


namespace Home\Controller;
use Think\Controller;
//上传永久素材
class ServiceController extends Controller {

    
    /*public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }*/

    public function testt(){
        $this->show("hello");
        echo "media";
    }
/*
test
*/    
    public function test_test()
    {
        $this->show("hello");
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
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);//在发起连接前等待的时间，如果设置为0，则无限等待
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
                $contentStr_content = "您发的是消息包含以下信息：\n发信人OpenID：".$fromUsername."\n收信人微信号：".$toUsername."\n发信时间：".$createTime."\n消息类型：".$msgType."\n消息内容：".$content."\n消息ID：".$msgId;    
                 $contentStr="您好，云牛为您服务^_^";
 
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
                $contentStr = "如需立即处理请发文本消息^_^,您发的消息类型不是文本。而是".$msgType;  
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);    
                echo $resultStr;    
            }
            
            $arr=array();
            $arr['fromUsername']=$fromUsername;
            $arr['toUsername']=$toUsername;
            $arr['createTime']=$createTime;
            $arr['msgType']=$msgType;
            $arr['content']=$content;
            $arr['msgId']=$msgId; 
            $this->data_save($arr);  
        }  
    } 


    //把消息用index控制器media_preview方法发至某人
    public function data_save($arr='')
    {       
            $arr['fromUsername']="fromUsername";
            $arr['toUsername']="toUsername";
            $arr['createTime']=3333;
            $arr['msgType']="msgType";
            $arr['content']="content";
            $arr['msgId']="msgId"; 
            $arr=json_encode($arr);
        $url='https://limingliming.xyz/wx/service.php';

        $result=$this->lm_curl_post($arr,$url);
        var_dump($result);
    }




    //客服接口-发消息
    public function custom_send()
    {
        $idnex=new IndexController();//构造函数获取$_SESSION['access_token'];
        $data='{"touser":"oB2KbwynfMIy-b1WTxwdfiEcZcu0","msgtype":"text","text":{"content":"Hello World"}}';
        $url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$_SESSION['access_token'];
        $result=$this->lm_curl_post($data,$url);
        var_dump($result);
    }
/*
{
    "touser":"OPENID",
    "msgtype":"text",
    "text":
    {
         "content":"Hello World"
    }
}
 */



}










