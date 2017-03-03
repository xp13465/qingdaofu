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
    var i = "<?php echo Yii::$app->request->get('i');?>";
    var wx = "<?php echo Yii::$app->params['wx'];?>";

    $(document).ready(function() {
        $('#test').diyUpload({
            url:"/fileupload.php",
            success: function (data) {
                var aa = $("input[name='" + typeName + "']").val();
                $("input[name='" + typeName + "']").val((aa ? (aa + ",") : '') + "'"+data.url+"'");
                if(i == 1){
                    $("input[name='" + typeName + "']").parent().parent().next().children().children('img').attr("src",wx+data.url);
                    $("input[name='" + typeName + "']").parent().parent().next().show();
                }else if(i == 2 || i == 3){
                    var img=new String();
                    var arr=new Array();
                    var img = $("input[name='" + typeName + "']").val();
                    arr=img.split(',');
                    var str = '';
                    $.each(arr,function(key,value){
                       values = value.substring(1,value.length-1);
                       var div = '<div class="photo_11" name='+key+'><span class="phc"><img src = "/images/close.gif" class="closebutton" style="position:absolute;z-index:20;display:none"/><img src='+wx+values+' style="height:50px;width:50px; display:inline-block;background-color:red;"/></span></div>';
                       str += div;
                    });
                    if(i == 2){
                        $("input[name='" + typeName + "']").parents('.pic').parent().children('.img-box').html(str);
                    }else if(i == 3){
                        $("input[name='" + typeName + "']").parents('.add').parent().children('.img-box').html(str);
                    }
                    $(".closebutton").click(
                        function(){
                            src = $(this).next('img').attr('src').replace(wx,'');
                            img = img.replace(src,"");
                            img = img.replace(",''","");
                            img = img.replace("'',","");
                            img = img.replace("''","");
                            $("input[name='" + typeName + "']").val(img);
                            $(this).parent().parent('div').remove();
                        }
                    );
                }

            },
            error: function (err) {
            },
            buttonText : '选择图片或者PDF',
            accept: {
                extensions:"gif,jpg,jpeg,bmp,png",
            }
        });
    });
</script>