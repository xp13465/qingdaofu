<div class="content_right">
    <div class="lvshi">
        <h3 class="bj">　　个人律师</h3>
        <ul class="c_right_2">
            <li><span>姓名：</span><?php echo isset($certi['name'])?$certi['name']:''?></li>
            <li>
                <span>身份证：</span><?php echo isset($certi['cardno'])?$certi['cardno']:''?><span class="suolve" status="0" style="color:#0061ac;">详情<i class="box"></i></span>
            </li>
            <li><span>联系地址：</span><?php echo isset($certi['address'])?$certi['address']:''?></li>
            <li><span>联系方式：</span><?php echo isset($certi['mobile'])?$certi['mobile']:'' ?></li>
            <li><span>律师执业证：</span><?php echo isset($certi['law_cardno'])?$certi['law_cardno']:''?><span class="suolve" status="1" style="color:#0061ac;">详情<i class="box"></i></span></li>
            <?php if($certi['email']){echo "<li>"."<span>邮箱：</span>".$certi['email']."<li>";}else{echo '';}?>
            <?php if($certi['education_level']){echo "<li>"."<span>学历：</span>".$certi['education_level']."<li>";}else{echo '';}?>
            <?php if($certi['lang']){echo "<li>"."<span>语言：</span>".$certi['lang']."<li>";}else{echo '';}?>
            <?php if($certi['working_life']){echo "<li>"."<span>从业年限：</span>".$certi['working_life']."<li>";}else{echo '';}?>
            <?php if($certi['professional_area']){echo "<li>"."<span>专业领域：</span>".$certi['professional_area']."<li>";}else{echo '';}?>
            <?php if($certi['casedesc']){echo "<li>"."<span>清收经典案例：</span>".$certi['casedesc']."<li>";}else{echo '';}?>
            <?php $car = unserialize($certi['cardimg'])?>
        </ul>
    </div>
</div>
<script type="text/javascript">
    $('.box').hide();
    $('.suolve').hover(function(){
        var img ={
            0:"<?php echo $car['cardimgs']?>",
            1:"<?php echo $car['law_cardnos']?>",
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