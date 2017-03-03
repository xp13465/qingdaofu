<?php

if(in_array($category,[1,2,3])){
    $finances = \common\models\Apply::find()->where(['product_id'=>$id,'category'=>$category,'app_id'=>0])->all();
}else{

}
if($category == 1){
    $desc = \common\models\FinanceProduct::findOne(['category'=>$category,'id'=>$id]);
}elseif(in_array($category,[2,3])){
    $desc = \common\models\CreditorProduct::findOne(['category'=>$category,'id'=>$id]);
}
$url = '';
if($category == 1){
    if($desc->uid == Yii::$app->user->getId()){
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacyfinancing','category'=>$category,'id'=>$id]);
    }else{
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacyinvestment','category'=>$category,'id'=>$id]);
    }
}elseif($category == 2){
    if($desc->uid == Yii::$app->user->getId()){
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacyentrust','category'=>$category,'id'=>$id]);
    }else{
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacycollection','category'=>$category,'id'=>$id]);
    }
}elseif($category == 3){
    if($desc->uid == Yii::$app->user->getId()){
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacylawentrust','category'=>$category,'id'=>$id]);
    }else{
        $url = \yii\helpers\Url::toRoute(['/protocol/mediacylawer','category'=>$category,'id'=>$id]);
    }
}
?>
<div class="apply">
    <span class="bord_l">申请记录</span>
    <!-- <i></i> -->
    <?php if(isset($finances[0]->id)){?>
        <div class="mytable" id="cd1">
            <table cellspacing="1" cellpadding="0">
                <thead>
                <tr>
                    <th>选择</th>
                    <th>区域</th>
                    <th>申请日期</th>
                    <th>接单方</th>
                    <th>代理人</th>
                    <th>联系方式</th>
                    <th>类型</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($finances as $v):
                    $user = \common\models\Certification::findOne(['uid'=>$v['uid']]);
                    $usermobile = \common\models\User::findOne(['id'=>$v['uid']]);
                    $trustee = \common\models\Certification::findOne(['uid'=>$usermobile['pid']]);
                    if(!isset($user->id))$username = \common\models\User::findOne(['id'=>$v['uid']]);

                    ?>
                    <tr>
                        <td><input type="radio" class="idlist" name="idlist[]" value="<?php echo $v['id']?>"/></td>
                        <td><?php echo isset($v['create_time'])?(\frontend\services\Func::getCityNameById($desc['city_id'])):''?></td>
                        <td><?php echo isset($v['create_time'])?date('Y年m月d日 H:i:s',$v['create_time']):''?></td>
                        <td><a href="<?php echo yii\helpers\Url::toRoute(['/publish/information','id'=>$user['id']])?>"><?php echo isset($user['uid'])?$user['name']:(isset($trustee->name)?$trustee->name:'');?></a></td>
                        <td><?php echo isset($user['contact'])?$user['contact']:(isset($username['username'])?$username['username']:'')?></td>
                        <td><?php if(isset($user['mobile'])&&$user['mobile']){echo $user['mobile'];}else{echo $usermobile['mobile'];}?></td>
                        <td><?php echo \frontend\services\Func::$category[$v['category']];?></td>
                    </tr>
                <?php
                unset($user);
                endforeach;?>
                </tbody>
            </table>
        </div>
        <!-- 确认按钮 -->
        <div class="affirm">
            <a id="apply_submit">确认</a>
        </div>
    <?php }else{echo '<p style="text-align:center;display:inline-block;width:840px;font-size:20px;">暂无<p>';}?>
</div>
<script type="text/javascript">
    $(function(){
        //确认事件
        $("#apply_submit").off().on("click", function(){
            if($("input[name='idlist[]']:checked").length > 0){
                //dopost..
                var idlist = '';
                $("input[name='idlist[]']:checked").each(function(){
                    idlist = $(this).val();
                });
                window.open ('<?php echo $url."&idlist=";?>'+idlist,'newwindow','top=0,left=0,toolbar=no,menubar=no,scrollbars=yes, resizable=no,location=no, status=no')
            }else{
                alert('请您选择要同意的用户!');
                return false;
            }
        });
    });
</script>