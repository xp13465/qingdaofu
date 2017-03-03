<?php
$this->title = '资格认证';
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
.confirmText{
	width:100%;
	height:100px;
	resize:none;
}
</style>
<div class="row">
    <div class="col-lg-12"> 
		<button type="button" class="btn btn-2g btn-primary"  id='outexcel'>导出</button>
        <input name='keyword' type='text' id='keyword' value='<?=isset($keyword)?$keyword:''?>'/>
		<button type="button" class="btn btn-2g btn-primary" id='seach'>查找</button>
		 
        <br /><br />
		<div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>序号</th>
                        <th>类型</th>
                        <th>用户姓名或者机构名称</th>
                        <th>联系地址</th>
                        <th>邮箱</th>
                        <th>联系方式</th>
                        <th>证件号码，身份证号或者营业执照</th>
                        <th>联系人</th>
                        <th width='100px'>时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $category = [1=>'个人',2=>'律所',3=>'机构'];
                foreach($certification  as $item => $ms){
                    $user = \common\models\User::findOne(['id'=>$ms->uid]);
                    ?>
                    <tr>
                        <td><?php echo $item+1;//$ms->id;?></td>
                        <td ><?php echo $category[$ms->category]?></td>
                        <td><?php echo $ms->name?></td>
                        <td><?php echo $ms->address?></td>
                        <td><?php echo $ms->email?></td>
                        <td><?php echo $ms->mobile?></td>
                        <td><?php echo $ms->cardno?></td>
                        <td><?php echo $ms->contact?></td>
                        <td><?php echo $ms->modifytime?></td>

                        <td style="color: red;">
                            <?php  if($ms->state == 0){?>
                                <a href="javascript:shenhe('<?php echo $ms->id?>')">审核</a>
                            <?php }else if($ms->state == 1){?>
                                已通过<?php }else{?>
                                已拒绝<?php }?>
            </>
            <?php if ($ms->category == 3){?>
                <a href="<?php echo \yii\helpers\Url::to(['/certification/add','id'=>$ms->id]);?>">设置经办人个数</a>
            <?php }?>
                        <a href="<?php echo yii\helpers\Url::to(['/certification/chakan','id'=>$ms->id])?>">查看</a>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
</table>
    </div>
</div>

        <div class="fenye"><?= \yii\widgets\LinkPager::widget([
                'firstPageLabel' => '首页',
                'lastPageLabel' => '最后一页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'pagination' => $pagination,
                'maxButtonCount'=>4,
            ]);?></div>
        <script type="text/javascript">
            function shenhe(id){
				layer.confirm('<textarea placeholder="备注原因。。" name="resultmemo" class="confirmText" ></textarea>', {
					title:'审核认证信息',
					btn: ['审核通过','审核失败','取消'] //按钮
				}, function(){
				    $.ajax({
                        url:"<?php echo \yii\helpers\Url::to('/certification/verify');?>",
                        type:'post',
                        data:{id:id,type:1,"resultmemo":$(".confirmText").val()},
                        success:function(html){
                            if(html != '1'){
                                layer.alert('操作失败');
                            }else{
                                window.location = window.location;
                            }
                        }
                    });
				}, function(){
				   $.ajax({
                        url:"<?php echo \yii\helpers\Url::to('/certification/verify');?>",
                        type:'post',
                        data:{id:id,type:2,"resultmemo":$(".confirmText").val()},
                        success:function(html){
                            if(html != '1'){
                                layer.alert('操作失败');
                            }else{
                                window.location = window.location;
                            }
                        }
                    });
				});
				return false;
                if(confirm('确定通过审核？')){
                   
                }else{
                    
                }
            }
			
			$(document).ready(function(){
				$('#seach').click(function(){
					var keyword = $('#keyword').val();
					self.location = '/certification/list?keyword='+keyword;
				});
				$('#outexcel').click(function(){
					var keyword = $('#keyword').val();
					window.open('/certification/output?keyword='+keyword);
				});
				
			})
        </script>

</div>