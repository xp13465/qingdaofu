<?php
echo \yii\helpers\Html::buttonInput("start",['class'=>'getCommunity']);
?>

<div id = "communitylist">
<?php echo $content?>
</div>
<script>

    $(document).ready(
        function (){
            callback(<?php echo $cnum?>);
        }
    );

    function callback(cnum){
        $('.li-itemmod').each(
            function () {
                $.ajax(
                    {
                        url:"http://zichanbao/community/getcc",
                        type:'post',
                        data:{name:$(this).find('a:first').attr('title')},
                        success: function () {
                            window.location = "/community/getcommunity?cnum="+(cnum+1);
                        }
                    }
                );
            }
        );
    }
</script>
