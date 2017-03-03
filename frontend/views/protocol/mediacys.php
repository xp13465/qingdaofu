<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;
//var_dump($data);die;
?>
<?php



    if ($data['create_by'] == Yii::$app->user->getId()) {
        //居间协议（清收委托人）
        echo $this->render('mediacyentrust',['data'=>$data]);
    } else {
        //居间协议（清收公司）
        echo $this->render('mediacycollection',['data'=>$data]);
    }


/*if ($data['category'] == 1) {
    if ($data['create_by'] == $user['id']) {
        //居间协议（融资人）
       echo $this->render('mediacyfinancing',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    } else {
        //居间协议（投资人）
        echo $this->render('mediacyinvestment',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    }
} elseif ($data['category'] == 2) {
    if ($data['uid'] == $user['id']) {
        //居间协议（清收委托人）
        echo $this->render('mediacyentrust',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    } else {
        //居间协议（清收公司）
        echo $this->render('mediacycollection',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    }
} elseif ($data['category'] == 3) {
    if ($data['uid'] == $user['id']) {
        //居间协议（法律服务-委托人）
        echo $this->render('mediacylawentrust',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    } else {
        //居间协议（法律服务-律师事务所）
        echo $this->render('mediacylawer',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    }
}*/
?>
<footer>
    <?php if(isset($data['progress_status'])&&$data['progress_status'] == 1){?><a href="#" class="agree" onclick="submitObj();">同意</a></p><?php }?>
</footer>

<script type="text/javascript">
    function submitObj(){
        var id = "<?php echo Yii::$app->request->get('id')?>";
        var uid = "<?php echo Yii::$app->request->get('uid')?>";
        var category = "<?php echo Yii::$app->request->get('category')?>";
        $.ajax({
            url:"<?php echo \yii\helpers\Url::toRoute('/usercenter/agreeorder')?>",
            type:'post',
            data:{id:id,uid:uid,category:category},
            dataType:'json',
            success:function(json){
                if(json.code == '0000'){
                    alert(json.msg);
                    location.href="<?php echo \yii\helpers\Url::toRoute('/usercenter/release')?>";
                }else{
                    alert(json.msg);
                }
            }
        })
    }
</script>


