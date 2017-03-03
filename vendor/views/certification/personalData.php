<div class="content_right">
    <h3 class="bj">　　个人清收师</h3>
    <div class="geren">
        <ul class="c_right_2">
            <li><span>姓名：</span><?php echo isset($certi['name'])?$certi['name']:''?></li>
            <li>
                <span>身份证：</span><?php echo isset($certi['cardno'])?$certi['cardno']:''?><span class="suolve" status="0" style="color:#0061ac;">详情<i class="box"></i></span>
            </li>
            <li><span>联系地址：</span><?php echo isset($certi['address'])?$certi['address']:''?></li>
            <li><span>联系方式：</span><?php echo isset($certi['mobile'])?$certi['mobile']:'' ?></li>
            <?php if($certi['email']){echo "<li>"."<span>邮箱：</span>".$certi['email']."<li>";}else{echo '';}?>
            <?php if($certi['casedesc']){echo "<li>"."<span>清收经典案例：</span>".$certi['casedesc']."<li>";}else{echo '';}?>
        </ul>
    </div>
    </div>
<script type="text/javascript">
    $('.box').hide();
    $('.suolve').hover(function(){
        var img ={
            0:"<?php echo $certi['cardimg']?>",
        };
        var img_index = $(this).attr('status');
        $('i.box').html('<img src="/'+img[img_index]+'" >');
        $('i.box').find('img').each(function(){
            var $imgH = $('i.box').outerHeight();
            var $imgW = $('i.box').outerWidth();
            $('i.box').find('img').css({
                "width":$imgW,
                "height":$imgH
            })

        })
        $(this).find('i').stop().fadeToggle(500);
    });
</script>