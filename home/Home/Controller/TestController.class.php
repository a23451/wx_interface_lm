<?ph    function __construct(){
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











    public function index()
    {
        echo "<hr>";
        echo "<a href='".__CONTROLLER__."/".__FUNCTION__."'>aaa</a>"; 
        echo "<hr>";
        echo "<a href='".__CONTROLLER__."/test1'>test1</a>"; 
        echo "<hr>";
        echo "<a href='".__CONTROLLER__."/test4'>test4</a>"; 
        $cla=new MediaController();

    }

    //上传缩略图，图文中图片表单
    public function test1()
    {
        $this->show();

    }

    //上传图文中图片
    public function test2()
    {
        $cla=new UploadTolocalController();
        $info=$cla->lm_upload_img();//上传至本地服务器返回本地文件地址
        echo "<hr>";
        $cla=new MediaController();
        $access_token=$_SESSION['access_token'];
        $data=array($info[1]=>$info[0]);
        $result=$cla->lm_media_uploadimg($data,$access_token);//上传至微信服务器返回本URL
        $_SESSION['file'][$info[1]]=$result;
        echo $result;
        var_dump($_SESSION['file'][$info[1]]);

    }

    //上传缩略图
    public function test3()
    {
        $cla=new UploadTolocalController();
        $info=$cla->lm_upload_img();//上传至本地服务器返回本地文件地址
        echo "<hr>";
        $this->test3_2($info);
    }
    //上传缩略图由本地至微信服务器
    public function test3_2($info)
    {
        $cla=new MediaController();
        $access_token=$_SESSION['access_token'];
        $data=array($info[1]=>$info[0]);
        if (!isset($_SESSION['thumb'][$info[1]])) {
            $type="thumb";
            $result=$cla->lm_media_upload($data,$access_token,$type)['thumb_media_id'];
            $_SESSION['thumb'][$info[1]]=$result;
            var_dump($_SESSION['thumb']);
        }else{
            print_r($_SESSION['thumb']);
        }

        

    }
    
    //上传图文消息表单
    public function test4()
    {        
        echo "url<br>";
        foreach ($_SESSION['file'] as $key => $value) {
           echo $key." : ".$value."<br>";
        }  
        echo "<hr>thumb_media_id<br>"; 
        foreach ($_SESSION['thumb'] as $key => $value) {
           echo $key." : ".$value."<br>";
        }
        $this->show();
    }
    //上传图文消息
    public function test5()
    {        

        $data=$_POST['content'];

        $access_token=$_SESSION['access_token'];

        $cla=new MediaController();
        $result=$cla->lm_media_uploadnews($data,$access_token);
        print_r($result);
        $_SESSION['newsID']=$result;

    }
    //sendall群发或预览
    public function test6()
    {

        $cla=new UploadTolocalController();
        $info=$cla->lm_download_img();//下载至本地服务器返回本地文件地址

    }





}