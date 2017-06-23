<?php
namespace Home\Controller;
use Think\Controller;
class MenuController extends ParentController {
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

    public function index()
    {
        echo "string";
    }

/*
获取自定义菜单配置接口
 */

    public function get_current_selfmenu_info()
    {
        $access_token=$_SESSION['access_token'];
        $url='https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token='.$access_token;
        $menu_info=$this->lm_curl_get($url);
        //$menu_info=json_decode($menu_info);
        var_dump($menu_info);

    }
/*
自定义菜单创建接口
 */

    public function menu_create()
    {
        $data=' {
             "button":[
             {
                   "name":"关于我们",
                   "sub_button":[
                   {    
                       "type":"view",
                       "name":"公司介绍",
                       "url":"http://www.cloudox.cn/qyjs/"
                    },
                    {    
                       "type":"view",
                       "name":"联系我们",
                       "url":"http://www.cloudox.cn/lxwm/"
                    }]
               },
             {  
                  "type":"view",
                  "name":"云牛系统",
                  "url":"http://www.cloudox.net"
              },
              {
                   "type":"button",
                   "name":"在线客服",
                   "value":"请留言我们会尽快回复您"
                   
               }]
         }';

 
        $access_token=$_SESSION['access_token'];
        $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
        $menu_info=$this->lm_curl_post($data,$url);
        //$menu_info=json_decode($menu_info);
        var_dump($menu_info);

    }



}