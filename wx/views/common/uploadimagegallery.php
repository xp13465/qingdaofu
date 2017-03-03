<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href='/js/diyUpload/css/webuploader.css'/>
    <link rel="stylesheet" type="text/css" href='/js/diyUpload/css/diyUpload.css'/>
    <script src="/js/diyUpload/js/webuploader.html5only.min.js"></script>
    <script src="/js/diyUpload/js/diyUpload.js"></script>

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
    var wx = "<?php echo Yii::$app->params['wx'];?>";
    $(document).ready(function() {
        $('#test').diyUpload({
            url:"/fileupload.php",
            success: function (data) {
                var aa = $("input[name='" + typeName + "']").val();
                $("input[name='" + typeName + "']").val((aa ? (aa + ",") : '') + "'"+data.url+"'");

                var strimg  = $("input[name='" + typeName + "']").val();
                if(getCookie(getCookie('publishCookieName')+'creditorfile') == null || getCookie(getCookie('publishCookieName')+'creditorfile') == '' ){
                    var  bb = [];
                    bb[typeName] = strimg;
                    setCookie(getCookie('publishCookieName')+'creditorfile',arrayserializa(bb),'h1');
                }else{
                    var cc = formUnserialize(getCookie(getCookie('publishCookieName')+'creditorfile'));
                    cc[typeName] = strimg;
                    setCookie(getCookie('publishCookieName')+'creditorfile',arrayserializa(cc),'h1');
                }
            },
            error: function (err) {
            },
            buttonText : '选择图片',
            accept: {
                extensions:"gif,jpg,jpeg,bmp,png",
            }
        });
    });

    var sint = setInterval("if(typeof isFinished == 'undefined'){}else{window.clearInterval(sint);alert('上传成功');window.location = document.referrer;}",100)
</script>