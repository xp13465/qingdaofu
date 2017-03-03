<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
?>
<?=wxHeaderWidget::widget(['title'=>'案例信息','gohtml'=>''])?>
<?php if($case){ ?>
<section>
        <div class="buchong">
            <ul>
                <li>
                    <span><?php echo isset($case)?$case:''?></span>
                </li>
            </ul>
        </div>
</section>
<?php } ?>



