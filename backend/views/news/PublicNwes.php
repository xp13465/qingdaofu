<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'NewsList','method'=>'post','action'=>"/news/personal",'options'=>['enctype'=>"multipart/form-data"]])?>
<script type="text/javascript">
    window.UEDITOR_HOME_URL = "/ueditor/";
</script>
<script type="text/javascript" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
    window.UEDITOR_CONFIG.initialFrameWidth = 665;
    UE.getEditor('myeditor');
</script>
<table class="table">
    <thead>
    <tr>
        <td colspan="2">个人、律师发布</td>
    </tr>
    </thead>
    <tr>
        <td align="right">姓名：</td>
        <td><?php echo html::input('text','name',isset($news['name'])?$news['name']:'')?><input type="file" accept = 'image/*' name="ClassicCase[picture]"><span class="color">上传 +</span>　<em name="picture"></em></td>

    </tr>
    <tr>
        <td align="right">职业：</td>
        <td><?php echo html::dropDownList('occupation',isset($news['occupation'])?$news['occupation']:'',\common\models\ClassicCase::$occupation)?></td>
    </tr>
    <tr>
        <td align="right">摘要：</td>
        <td><?php echo html::input('text','abstract',isset($news['abstract'])?$news['abstract']:'')?></td>
    </tr>
    <tr>
        <td align="right">案例：</td>
        <td><?php echo html::textarea('casetext',isset($news['casetext'])?$news['casetext']:'',['id'=>'myeditor','style'=>'border:none;width:100%;height:300px;'])?></td>
    </tr>
    <tr><td colspan="2" align="center"><input type="submit" class="submit" value="保存添加" onclick="news()"/></td></tr>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        $("#NewsList").validate({
            ignore:"",
            rules: {
                name:"required",
                "ClassicCase[picture]":'required',
                profession:'required',
                casetext:"required",
                abstract:'required',
            },
            messages: {
                name:"请输入姓名",
                "ClassicCase[picture]":'请上传图片',
                profession:'请输入职业',
                casetext:"请输入案例",
                abstract:'请输入摘要',
            },
        });
    });
    function news(){
        var r = $("#NewsList").valid()
        $("#NewsList").submit();
    }
    $(function() {
        $(':file').click(function () {
            $('input[name="ClassicCase[picture]"]').change(function (a) {
                if (a) {
                    $('em[name=picture]').css('color', 'red');
                    $('em[name=picture]').html('已上传');
                }
            })

        })
    })
</script>