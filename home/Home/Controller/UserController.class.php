<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends ParentController {
    function __construct(){
        parent::__construct();
        $filepath='access_token.txt';
        if (!isset($_SESSION['access_token'])||!isset($_SESSION['access_token_time'])) {
            echo "no session<hr>";
            if (file_exists($filepath)) {
                echo "file_exit<hr>";
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
                echo "no file<hr>";
                $access_token_array=$this->creat_token($filepath);/////////////access_token存入$_SESSION写入文件
            }
        }else{
            $time=time()-$_SESSION['access_token_time'];
            if($time>7000){
                $access_token_array=$this->creat_token($filepath);/////////////access_token存入$_SESSION写入文件
            }
        }
            //echo "<hr>access_token::".$_SESSION['access_token']."<hr>";
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

获取
 */
public function get_user_list($next_openid='')
{
    if ($next_openid) {
        $next_openid='&next_openid='.$next_openid;
    }
    $access_token=$_SESSION['access_token'];
    $url='https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token.$next_openid;
    $result=$this->lm_curl_get($url);
    var_dump($result);
}







/*
标签管理
1. 创建标签
 */
public function creat_tag($value='all')
{
    if (isset($_GET['tagName'])) {
        $value=$_GET['tagName'];
    }
    $access_token=$_SESSION['access_token'];
    $url='https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$access_token;
    $data='{ "tag" : { "name" : "'.$value.'" } }';
    $result=$this->lm_curl_post($data,$url);
    var_dump(json_decode($result));
}

/*
$data='{
  "tag" : {
    "name" : "广东"//标签名
  }
}'
 */




/*
2. 获取公众号已创建的标签
 */
public function tags_get()
{
    
    $access_token=$_SESSION['access_token'];
    $url='https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$access_token;
    $result=$this->lm_curl_get($url);
    var_dump($result);
    return $result;
}





/*
3. 编辑标签
$data=array('id'=>101,'name'=>'标签名');未测试
*/
public function tags_update($data)
{
    $data='{
  "tag" : {
    "id" : '.$data['id'].',
    "name" : "'.$data['name'].'"
  }
}';
    $access_token=$_SESSION['access_token'];
    $url='https://api.weixin.qq.com/cgi-bin/tags/update?access_token='.$access_token;
    $result=$this->lm_curl_post($data,$url);
    var_dump($result);
    return $result;
}

/*
$data='{
  "tag" : {
    "id" : 134,
    "name" : "广东人"
  }
}'
 */




/*
4. 删除标签
$data=101;   标签名  未测试
 */
public function tags_update2($data)
{
    $data='{
  "tag":{
       "id" : '.$data.'
  }
}';
    $access_token=$_SESSION['access_token'];
    $url='https://api.weixin.qq.com/cgi-bin/tags/delete?access_token='.$access_token;
    $result=$this->lm_curl_post($data,$url);
    var_dump($result);
    return $result;
}
/*
$data='{
  "tag":{
       "id" : 134
  }
}'
 */





/*
5. 获取标签下粉丝列表
"next_openid":""//第一个拉取的OPENID，不填默认从头开始拉取   未测试
 */
public function tags_update3($data)
{
    if (isset($data['next_openid'])) {
       $data['next_openid']=',"next_openid":"'.$data['next_openid'].'"';
    }
    $data='{ "tagid" : '.$data['tagid'].$data['next_openid'].'}';
    $access_token=$_SESSION['access_token'];
    $url='https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token='.$access_token;
    $result=$this->lm_curl_post($data,$url);
    var_dump($result);
    return $result;
}
/*
$data='{
  "tagid" : 134,
  "next_openid":""//第一个拉取的OPENID，不填默认从头开始拉取
}'
 */







/*
用户管理
1. 批量为用户打标签
 */
public function batchtagging($openid_list=array('oB2KbwynfMIy-b1WTxwdfiEcZcu0'),$tag=100)
{

    $access_token=$_SESSION['access_token'];
    $url='https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token='.$access_token;

    foreach ($openid_list as $key => $value) {
        $value='"'.$value.'"';
    }
    $openid_list_str=implode(',',$openid_list);


    $data='{
  "openid_list" : ['.$openid_list_str.'
  ],
  "tagid" : '.$tag.'
}';
    $result=$this->lm_curl_post($data,$url);
    var_dump(json_decode($result));
}
/*
$data='{
  "openid_list" : [//粉丝列表
    "ocYxcuAEy30bX0NXmGn4ypqx3tI0",
    "ocYxcuBt0mRugKZ7tGAHPnUaOW7Y"
  ],
  "tagid" : 134
}';
 */



/*

2批量为用户取消标签
 */
public function batchuntagging($openid_list=array('oB2KbwynfMIy-b1WTxwdfiEcZcu0'),$tag)
{

    $access_token=$_SESSION['access_token'];
    $url='https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token='.$access_token;

    foreach ($openid_list as $key => $value) {
        $value='"'.$value.'"';
    }
    $openid_list_str=implode(',',$openid_list);


    $data='{
  "openid_list" : ['.$openid_list_str.'
  ],
  "tagid" : '.$tag.'
}';
    $result=$this->lm_curl_post($data,$url);
    var_dump(json_decode($result));
}
/*
$data='{
  "openid_list" : [//粉丝列表
    "ocYxcuAEy30bX0NXmGn4ypqx3tI0",
    "ocYxcuBt0mRugKZ7tGAHPnUaOW7Y"
  ],
  "tagid" : 134
}';
 */


/*

3. 获取用户身上的标签列表
 */
public function getidlist($openid)
{

    $access_token=$_SESSION['access_token'];
    $url='https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token='.$access_token;

    $data='{
      "openid" : "'.$openid.'"
    }';
    $result=$this->lm_curl_post($data,$url);
    var_dump(json_decode($result));
}
/*
$data='{
  "openid" : "ocYxcuBt0mRugKZ7tGAHPnUaOW7Y"
}';
 */

public function user_info()
{
    $arr=$this->get_user_list();
    $arr=json_decode($arr,true);
var_dump($arr);
    $access_token=$_SESSION['access_token'];
echo $access_token."<hr>";
    $url='https://api.weixin.qq.com/cgi-bin/user/info?lang=zh_CN&openid=oyzvBtyObdaQb9jLMxDNdvTWIRCI&access_token='.$access_token;
    $info=$this->lm_curl_get($url);
    var_dump($info);
}






}