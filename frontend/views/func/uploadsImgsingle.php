<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href='/diyUpload/css/webuploader.css'/>
    <link rel="stylesheet" type="text/css" href='/diyUpload/css/diyUpload.css'/>
    <script src="/js/jquery-1.11.1.js" data-content-id="sb"></script>
    <script src="/diyUpload/js/webuploader.html5only.min.js"></script>
    <script src="/diyUpload/js/diyUpload.js"></script>

    <style>
        #demo{ margin:0px; width:570px;height:auto;}
    </style>
</head>
<body>
<div id = "demo"><div id="test"></div></div>
</body>
</html>
<script>
    var typeName = '<?php echo Yii::$app->request->get('type');?>';

    $(document).ready(function() {
        $('#test').diyUpload({
            url:"/fileupload.php",
            success: function (data) {
                var aa = $("input[name='" + typeName + "']").val();
                $("input[name='" + typeName + "']").val((aa ? (aa + ",") : '') + "'"+data.url+"'");
                $("input[name='" + typeName + "']").next().next('span').children('img').attr("src",data.url);
                $("input[name='" + typeName + "']").next().next('span').show();
                //$("input[name='" + typeName + "']").next('span').remove();
            },
            error: function (err) {
            },
            fileNumLimit:1,
            buttonText : '选择图片或者PDF',
            accept: {
                extensions:"gif,jpg,jpeg,bmp,png,pdf",
            }
        });
    });
</script>