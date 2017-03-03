<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use wx\widget\wxHeaderWidget;

    $url = "#";
    $compile = "";
    if($product['category'] == 1 && $product['uid'] == $uid && in_array($product['progress_status'],[0,1])){
        $url = Url::toRoute(['/publish/editfinancing','category'=>'1','id'=>$product['id']]);
        $compile = "编辑";
    }else if(\yii\helpers\ArrayHelper::isIn($product['category'],[2]) && $product['uid'] == $uid && in_array($product['progress_status'],[0,1])){
        $url = Url::toRoute(['/publish/editcollection','category'=>$product['category'],'id'=>$product['id']]);
        $compile = "编辑";
    }elseif(\yii\helpers\ArrayHelper::isIn($product['category'],[3]) && $product['uid'] == $uid && in_array($product['progress_status'],[0,1])){
        $url = Url::toRoute(['/publish/editligitation','category'=>$product['category'],'id'=>$product['id']]);
        $compile = "编辑";
    }
?>
<?php if($product['progress_status'] == 0){ ?>
    <footer>
        <div class="publish_b">
            <a href="javascript:void(0);" class="publi" data-id="<?php echo $product['id']?>" data-category="<?php echo $product['category']?>">立即发布</a>
        </div>
    </footer>
<?php }else if($product['progress_status'] == 1 && $product['uid'] == $uid){ ?>
  <footer>
     <div class="foot">
       <a href="javascript:viod(0);" data-id="<?php echo $product['id'];?>" data-category="<?php echo $product['category'];?>" data-type="2" class="modify btn btn-del"><code>删除产品</code></a>
       <a href="<?php echo $url ?>" class="btn btn-edit"><code>编辑信息</code></a>
     </div>
  </footer>
<?php }else if($product['progress_status'] == 2 && $product['uid'] == $uid){ ?>
            <?php if($product['applyclose'] == 0){ ?>
			    <footer>
                     <div class="foot"> 
                       <a href="javascript:void(0);" class="termination" data-id="<?php echo $product['id']?>" data-category="<?php echo $product['category']?>" class="modify btn btn-over"><code>终止</code></a> 
                       <a href="javascript:void(0);" class="closed" data-id="<?php echo $product['id']?>" data-category="<?php echo $product['category']?>" class="modify btn btn-apply"><code>申请结案</code></a>
			        </div>
               </footer>
            <?php }else if($product['applyclose'] == 4 && $product['applyclosefrom'] != $product['uid'] ){ ?>
                <footer>
				<div class="case">
                   <a href="javascript:void(0);" class="closed" data-id="<?php echo $product['id']?>" data-category="<?php echo $product['category']?>" data-type ="1">同意结案</a>
                </div>
				</footer>
            <?php }else{ ?>
                <footer>
                  <div class="foot">
                    <a href="#" style="color:#fff;background:#979797;width:100%;height:50px;float:left;line-height:50px;text-align:center;line-height:50px;font-size:16px;">已申请结案,等待对方确认中</a>
                   </div>
               </footer>
            <?php } ?>
<?php }else if($product['progress_status'] == 2 && $product['uid'] != $uid){ ?>

        <?php if($product['applyclose'] == 0){ ?>
		 <footer>
            <div class="case">
                <a href="javascript:void(0);" class="closed" data-id="<?php echo $product['id']?>" data-category="<?php echo $product['category']?>">申请结案</a>
            </div>
			 </footer>
        <?php }else if($product['applyclose'] == 4 && $product['applyclosefrom'] == $product['uid']){ ?>
            <footer>
			<div class="case">
                <a href="javascript:void(0);" class="closed" data-id="<?php echo $product['id']?>" data-category="<?php echo $product['category']?>" data-type ="1">同意结案</a>
            </div>
			 </footer>
        <?php }else{ ?>
            <footer>
                  <div class="foot">
                    <a href="javascript:viod(0);" data-id="<?php echo $product['id'];?>" data-category="<?php echo $product['category'];?>" class="modify" style="color:#fff;background:#979797;width:100%;height:50px;float:left;line-height:50px;text-align:center;line-height:50px;font-size:16px;">已申请结案,等待对方确认中</a>
                  </div>
            </footer>
        <?php } ?>

<?php }else if($product['progress_status'] == 3){ ?>
    <footer>
       <div class="foot">
	   <?php if($product['uid'] != $uid){ ?>
	   <a href="#" data-id="<?php echo $data['id'];?>" data-type = "1" style="color:#666;background:#fff url(images/ig02.jpg) 250px 10px no-repeat;width:100%;height:50px;float:left;line-height:50px;text-align:center;line-height:50px;font-size:16px;" class="modify">删除产品</a>
	   <?php }else{ ?>
       <a href="#" data-id="<?php echo $product['id'];?>" data-type="2" data-category="<?php echo $product['category'];?>" style="color:#666;background:#fff url(images/ig02.jpg) 250px 10px no-repeat;width:100%;height:50px;float:left;line-height:50px;text-align:center;line-height:50px;font-size:16px;" class="modify">删除产品</a>
       <?php } ?>
	   </div>
    </footer>
<?php }else if($product['progress_status'] == 4 ){ ?>

  <footer>
     <div class="foot">
	 <?php if($product['uid'] != $uid){ ?>
       <a href="javascript:void(0);" data-id="<?php echo $data['id'];?>" data-type = "1" class="modify"><span>删除产品</span></a>
     <?php }else{ ?>
	   <a href="javascript:void(0);" data-type="2" data-id="<?php echo $product['id'];?>" data-category="<?php echo $product['category'];?>" class="modify"><span>删除产品</span></a>
     <?php } ?>	 
	  <?php if($creditor >= 1){ ?>
	   <a href="<?php echo yii\helpers\Url::toRoute(['/usercenter/evaluatelists','id'=>$product['id'],'category'=>$product['category']])?>"><?php if($product['uid'] == $uid){echo "评价接单方";}else{echo"评价接单方";}?></a>
       <?php }else{ ?>
	    <a href="<?php echo yii\helpers\Url::toRoute(['/releases/addevaluation','id'=>$product['id'],'category'=>$product['category']])?>"><?php if($product['uid'] == $uid){echo "评价接单方";}else{echo"评价接单方";}?></a>
	   <?php } ?>
	 </div>
  </footer>
<?php }else if($product['progress_status'] == 1 && $product['uid'] != $uid && $data['app_id'] == 0) { ?>
     <footer>
       <div class="foot">
	        <a href="javascript:void(0);" class="quxiao" data-id="<?php echo $data['id']?>" style="color:#666;background:#fff url(images/ig02.jpg) 250px 10px no-repeat;width:100%;height:50px;float:left;line-height:50px;text-align:center;line-height:50px;font-size:16px;">取消申请</a>
       </div>
    </footer>
<?php }?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.closed').click(function () {
            var id = $(this).attr('data-id');
            var category = $(this).attr('data-category');
			var type = $(this).attr('data-type');
            if(confirm("请问是否结案")) {
                $.ajax({
                    url: "<?php echo yii\helpers\Url::toRoute('/releases/closed')?>",
                    type: 'post',
                    data: {id: id, category: category, status: 4},
                    dataType: 'json',
                    success: function (json) {
                        if(json.code == '0000'){
							if(type == 1){
								var confirmindex = layer.confirm("请问是否需要去评价？",{
									btn:['确定','取消']
								},function(){
									location.href= "<?php echo yii\helpers\Url::toRoute('/releases/addevaluation')?>?id="+id+"&category="+category;
			                        layer.close(confirmindex)
								},function(){
									location.reload();
								})
							}else{
							   location.reload();	
							}
                        }else{
                            alert(json.msg);
                        }
                    }
                });
            }
        })

        $('.termination').click(function () {
            var id = $(this).attr('data-id');
            var category = $(this).attr('data-category');
            if(confirm("请问是否终止")) {
                $.ajax({
                    url: "<?php echo yii\helpers\Url::toRoute('/releases/closed')?>",
                    type: 'post',
                    data: {id: id, category: category, status: 3},
                    dataType: 'json',
                    success: function (json) {
                        if(json.code == '0000'){
                            location.reload();
                        }else{
                            alert(json.msg);
                        }
                    }
                });
            }
        })

        $('.publi').click(function(){
            var id = $(this).attr('data-id');
            var category = $(this).attr('data-category');
            $.ajax({
                url:"<?php echo yii\helpers\Url::toRoute('/releases/releaselist')?>",
                type:'post',
                data:{id:id,category:category},
                dataType:'json',
                success:function(json){
                    if(json.code == '0000'){
                        location.href = "<?php echo yii\helpers\Url::toRoute(['/usercenter/release','status'=>1])?>";
                    }else{
                        alert(json.msg);
                    }
                }
            })

        })
        $('.modify').click(function(){
			var type = $(this).attr('data-type');
			if(type == 1){
				var id = $(this).attr('data-id');
				var data = {id:id,type:type};
			}else{
				var id = $(this).attr('data-id');
                var category = $(this).attr('data-category');
				var data= {id:id,category:category,type:type};
			}
			
			if(confirm("请问是否删除该条数据")) {
                $.ajax({
                    url: "<?php echo yii\helpers\Url::toRoute('/releases/deleteproduct')?>",
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    success: function (json) {
                        if(json.code == '0000'){
							if(type==1){
								location.href = "<?php echo yii\helpers\Url::toRoute(['/usercenter/orders','progress_status'=>'0'])?>";
							}else{
								location.href = "<?php echo yii\helpers\Url::toRoute(['/usercenter/release','status'=>'0'])?>";
							}   
                        }else{
                            alert(json.msg);
                        }
                    }
                });
            }
		})
		
		$('.quxiao').click(function(){
			var id = $(this).attr('data-id');
			if(confirm("请问是否取消收藏")){
				$.ajax({
					url:"<?php echo yii\helpers\Url::toRoute('/releases/cancels')?>",
					type:'post',
					data:{id:id},
					dataType:'json',
					success:function(json){
						if(json.code == '0000'){
							alert(json.msg);
							location.href="<?php echo yii\helpers\Url::toRoute(['/usercenter/orders','progress_status'=>'0'])?>";
						}else{
							alert(json.msg);
						}
					}
					
				})
			}
		})
    })
</script>
