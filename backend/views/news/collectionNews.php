<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

$this->title = (isset($news['id'])?'编辑':'添加').'债权和清收新闻';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php \yii\widgets\ActiveForm::begin(['id'=>'NewsList','method'=>'post','action'=>"/news/collection-news"])?>
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
                    <td colspan="2">添加债权和清收新闻</td>
                </tr>-->
                </thead>
                <tr>
                    <td align="right">标题：</td>
                    <td><?php echo html::input('text','title',isset($news['title'])?$news['title']:'')?></td>
                </tr>
                <tr>
                    <td align="right">类型：</td>
                    <td>
					<?php echo Html::dropDownList('category', $news['category']?:'', \common\models\News::$category, ['class' => 'dropdownlist']); ?>
					</td>
                </tr>
				<tr>
                    <td align="right">发布时间：</td>
                    <td>
					<?php echo DateTimePicker::widget( [
							'name' => 'create_time',
							'value'=>date('Y-m-d H:i:s',($news['create_time']?:time())),
							'type' => 2, 
							"options"=>['placeholder' => '不填为不失效'],
							'pluginOptions' => [
								'todayHighlight' => true,
								'autoclose' => true,
								'format' => 'yyyy-mm-dd hh:ii:ss',
							]
						]);
					
					
					
					?>
					</td>
                </tr>
                <!--<tr>
                    <td align="right">摘要：</td>
                    <td><?php /*echo html::input('text','abstract',isset($news['abstract'])?$news['abstract']:'')*/?></td>
                </tr>-->
                <tr>
                    <td align="right">内容：</td>
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
               // abstract:'required',
                content:"required",
                //category:"required",
            },

            messages: {
                title:"请输入标题",
                //abstract:'请输入摘要',
                content:"请输入内容",
                //category:"请选择类型",
            },
        });
    });
    function news(){
        var r = $("#NewsList").valid()
        $("#NewsList").submit();
    }
</script>
