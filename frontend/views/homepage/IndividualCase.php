            <ul class="clearfix">
             
                <?php 
                $count = count($individual);
                for($i=0;$i<$count;$i++){
                    if($i%3==0 ){
                        ?>
                        <li clas="clearfix">
                        <?php
                        
                        $y = $i;
                        $max = ($i+3)<$count?($i+3):$count;


                        for ($y =$i; $y < $max; $y++) { 
                            ?>
                            <a href="<?php echo yii\helpers\Url::toRoute(['/homepage/introduce','id'=>$individual[$y]['id']])?>" class="i_first">
                                <img src="<?php echo 'http://admin.zcb2016.com/'.$individual[$y]['picture'];?>" height="84" width="84" alt="">
                                <p class="color"><?php echo isset($individual[$y]['name'])?$individual[$y]['name']:''?></p>
                                <p class="t_1"><?php echo mb_substr($individual[$y]['abstract'],0,32,'utf-8').'...';?></p>
                            </a>

                            <?php
                        }
                ?>
                
                   
                </li>    
                    <?php }?>            
                <?php 

            }
                ?>
            </ul>

<script>
    $(function(){
        var n=0;
        var m=$('.jieshao li:last').index();
        $('.jieshao li:first').clone().appendTo('.jieshao ul');
        $('.turn').click(function(){
            n++;
            if(n>m)
            {n=0; };

            $('.jieshao ul').stop().animate({'left':'-'+n*$('.jieshao ul li').width()+'px'});
        })
    })
</script>

