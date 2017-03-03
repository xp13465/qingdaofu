<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'代理人列表','gohtml'=>'<a href="javascript:void(0);" class="add">继续添加</a>'])?>
<?php if(!empty($user) && $user != ''){ ?>
<section>
    <div class="agent-list">
            <?php foreach($user as $value){ ?>
                <a href="<?php echo yii\helpers\Url::toRoute(['/agent/agentexhibition','id'=>$value['id']])?>">
                    <ul>
                        <li <?php if($value['isstop'] == 1){echo 'class="white"';}?>>
                            <span><?php echo isset($value['username'])?$value['username']:''?><?php if($value['isstop'] == 1){echo '[停用]';}?></span>
                            <div>
                                <span><a href="javascript:void(0)"><?php echo isset($value['mobile'])?$value['mobile']:''?></a></span>
                                <i class="ag-icon"></i>
                            </div>
                        </li>
                    </ul>
                </a>
            <?php } ?>
    </div>
</section>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.add').click(function(){
            window.location = "<?php echo yii\helpers\Url::toRoute('/agent/addagent')?>";
        })
    })
</script>
