<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta charset="utf-8">
</head>
<body>
    上传图文中的图片
    <form method="post" enctype="multipart/form-data" action="/wxx/index.php/Home/Index/test2">
    <!-- <form method="post" enctype="multipart/form-data" action="http://localhost/wx/z.php"> -->
    <!-- <form method="post" action="http://localhost/wx/index.php"> -->

    <label for="file">文件名：</label>
        <input type="file" name="file" id="asss" />
        <button type="submit">submit</button>
    </form>
    <hr>
    上传缩略图
    <form method="post" enctype="multipart/form-data" action="/wxx/index.php/Home/Index/test3">
    <!-- <form method="post" enctype="multipart/form-data" action="http://localhost/wx/z.php"> -->
    <!-- <form method="post" action="http://localhost/wx/index.php"> -->

    <label for="file">文件名：</label>
        <input type="file" name="file" id="asss" />
        <button type="submit">submit</button>
    </form>
</body>
<!-- 上传图片生成URL -->
</html>