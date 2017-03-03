<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href='/js/diyUpload/css/webuploader.css'/>
    <link rel="stylesheet" type="text/css" href='/js/diyUpload/css/diyUpload.css'/>
    <script src="/js/diyUpload/js/webuploader.html5only.min.js"></script>
    <script src="/js/diyUpload/js/diyUpload.js"></script>

    <style>
        #demo{ margin:0px; width:180px;height:auto;  margin:0 auto;}
		.webuploader-container{  text-align: center}
    </style>
</head>
<body>
<div id = "demo"><div id="test"></div></div>
</body>
</html>
<script>
    var typeName = '<?php echo Yii::$app->request->get('type');?>';
    var data_url = '<?php echo Yii::$app->request->get('data_url');?>';
    var limit = parseInt('<?php echo Yii::$app->request->get('limit');?>');
    var i = "<?php echo Yii::$app->request->get('i');?>";
    var wx = "<?php echo Yii::$app->params['wx'];?>";
	var value = $("input[name='" + typeName + "']").val();
	var curlimit = value.split(",").length;
    $(document).ready(function() {
        $('#test').diyUpload({
            url:"<?php echo yii\helpers\Url::toRoute(['/common/upload','filetype'=>1,'_csrf'=>Yii::$app->getRequest()->getCsrfToken()])?>",
            success: function (data) {
                if(data.code == '0000'&&data.result.fileid){
                    var aa = $("input[name='" + typeName + "']").val();
                    if(limit&&aa.split(",").length>=limit){
                        alert("最多上传"+limit+"张图片");
                        return false;
                    }
                    //var u = $("input[name='" + typeName + "']").attr('data_url');
                    $("input[name='" + typeName + "']").val((aa ? (aa + ",") : '')+data.result.fileid).trigger("change");
                    //$("input[name='" + typeName + "']").attr('data_url',(u ? (u + ",") : '')+data.result.url);
                    if(i == 2 || i == 3){
                        var img=new String();
                        var arr=new Array();
                        var img = $("input[name='" + typeName + "']").attr('data_url');
                        arr=img.split(',');
                        var str = '';
                        var div ='<span class="phc"  ><img src = "/images/close.gif" class="closebutton"/> <img class="imagePreview" data_bid='+data.result.fileid+' src='+wx+data.result.url+' style=" display:inline-block;"/></span>';
                        if(i == 2){
                            var spandiv = $("input[name='" + typeName + "']").parent("span");
                            if(spandiv.index()==0){
                                spandiv.parent().prepend(div)
                            }else{
                                spandiv.prev().after(div);
                            }
                            //alert(spandiv.parent().children().size());
                            
                        }else if(i == 3){
                            var spandiv = $("input[name='" + typeName + "']").parent("span");
                            if(spandiv.index()==0){
                                spandiv.parent().prepend(div)
                            }else{
                                spandiv.prev().after(div);
                            }
                        }
                         
                   
					} 
                }else if(data.msg){
					alert(data.msg)
				}
                

            },
            error: function (err) {
				alert(err)
            },
			// chunked : false,
			// chunkSize : 512 * 1024,
			fileNumLimit : (limit-curlimit)?(limit-curlimit):-1,
			// fileSizeLimit : 500000 * 1024,
			// fileSingleSizeLimit : 50000 * 1024,
            buttonText : '选择图片',
            accept: {
                extensions:"gif,jpg,jpeg,bmp,png",
            }
        });
    });
</script>