<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title = (isset($news['id'])?'编辑':'添加').'新闻';
$this->params['breadcrumbs'][] = $this->title;
?>



    <!-- Bootstrap Core JavaScript -->
    <script src="/js/bootstrap.min.js"></script>
<?php \yii\widgets\ActiveForm::begin(['id'=>'NewsList','method'=>'post','action'=>"/news/addnews"])?>

<!-- 引入百度编辑器 -->
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
<?php echo html::input('hidden','id',isset($news['id'])?$news['id']:'')?>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
    <thead>
    <!--<tr>
        <td colspan="2">添加新闻</td>
    </tr>-->
    </thead>
    <tr>
        <td align="right">新闻标题：</td>
        <td><?php echo html::input('text','title',isset($news['title'])?$news['title']:'')?></td>
    </tr>
    <!--<tr>
        <td align="right">新闻摘要：</td>
        <td><?php /*echo html::input('text','abstract',isset($news['abstract'])?$news['abstract']:'')*/?></td>
    </tr>-->
    <tr>
        <td align="right">新闻内容：</td>
        <td><?php echo html::textarea('content',isset($news['content'])?$news['content']:'',['id'=>'myeditor','style'=>'border:none;width:100%;height:300px;'])?></td>
    </tr>
    <tr><td colspan="2" align="center"><input type="submit" class="submit" value="保存添加" onclick="news()"/></td></tr>
</table>
</div>
</div>
</div>
<?php \yii\widgets\ActiveForm::end();?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#NewsList").validate({
            ignore:"",
            rules: {
                title:"required",
                //abstract:'required',
                content:"required",
            },

            messages: {
                title:"请输入标题",
                //abstract:'请输入摘要',
                content:"请输入内容",
            },
        });
    });
    function news(){
        var r = $("#NewsList").valid()
        $("#NewsList").submit();
    }
</script>