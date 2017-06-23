<?php
namespace Home\Controller;
use Think\Controller;
//上传至本地服务器
class UploadTolocalController extends Controller {

    public function lm_upload_img($value='')
    {
        
        // 允许上传的图片后缀
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["file"]["name"]);
        echo $_FILES["file"]["size"];
        $extension = end($temp);     // 获取文件后缀名
        if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/jpg")
        || ($_FILES["file"]["type"] == "image/pjpeg")
        || ($_FILES["file"]["type"] == "image/x-png")
        || ($_FILES["file"]["type"] == "image/png"))
        && 
        ($_FILES["file"]["size"] < 10240000)   // 小于 10M
        && in_array($extension, $allowedExts))
        {
            if ($_FILES["file"]["error"] > 0)
            {
                echo "错误：: " . $_FILES["file"]["error"] . "<br>";
            }
            else
            {
                echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
                echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
                echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
                echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
                
                // 判断当期目录下的 upload 目录是否存在该文件
                // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
                if (file_exists("upload/" . $_FILES["file"]["name"]))
                {
                    echo $_FILES["file"]["name"] . " 文件已经存在。 ".UPLOADPATH."\upload\\" . $_FILES["file"]["name"];
                    if (!isset($_SESSION['file'][$_FILES["file"]["name"]])) {
                        $path="@".UPLOADPATH."\upload\\" . $_FILES["file"]["name"];
                        $_SESSION['file'][$_FILES["file"]["name"]]=$path;
                    }
                    return array($_SESSION['file'][$_FILES["file"]["name"]],$_FILES["file"]["name"]);
                }
                else
                {
                    // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                    move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
                    echo "文件存储在: " .UPLOADPATH."\upload\\" . $_FILES["file"]["name"];
                    $path="@".UPLOADPATH."\upload\\" . $_FILES["file"]["name"];
                    $_SESSION['file'][$_FILES["file"]["name"]]=$path;
                    return array($_SESSION['file'][$_FILES["file"]["name"]],$_FILES["file"]["name"]);
                }
            }
        }
        else
        {
            echo "非法的文件格式";

        }
        
    }



    /*
    *功能：php完美实现下载远程图片保存到本地
    *参数：文件url
    *当保存文件名称为空时则使用远程文件原来的名称
    *$url图片的网络地址http://localhost/wordpress/wp-content/uploads/2017/05/KWLQOQZ53IMM_WHDZ2P4H9.png
    *返回array('save_path'=>'@'.$save_dir.$filename)
    */

public function lm_download_img($url)
{
    $save_dir=UPLOADPATH."\download\\";
/*    
以下判断用文件原名作为文件名，但如果下载重名文件，会在原文件基础上下载，文件大小会叠加。
    if ($filename=strrchr($url,'/')) {
        $filename=substr($filename,1);
    }else{
        $ext=strrchr($url,'.');
        $filename=time().$ext;
    }
*/
        $ext=strrchr($url,'.');
        $filename=time().$ext;
    // echo "<hr>dir:".$save_dir."<hr>";
    // echo "<hr>name:".$filename."<hr>";

        //获取远程文件所采用的方法
        $type=1;
        if($type){
        $ch=curl_init();
        $timeout=5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $img=curl_exec($ch);
        curl_close($ch);
        }else{
         ob_start();
         readfile($url);
         $img=ob_get_contents();
         ob_end_clean();
        }
/*用来检查下载的文件名，保存的文件名，文件大小
        $size=strlen($img);
        echo "strrchr($url,'.'):".$filename.":size".$size;
        //文件大小
 */        

        $fp2=@fopen($save_dir.$filename,'a');
        fwrite($fp2,$img);
        fclose($fp2);
       unset($img,$url);echo "<hr>";
        return array('file'=>'@'.$save_dir.$filename);
        //return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);

}





}