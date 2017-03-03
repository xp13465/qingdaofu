<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href='/diyUpload/css/webuploader.css'/>
    <link rel="stylesheet" type="text/css" href='/diyUpload/css/diyUpload.css'/>
    <script src="/js/jquery-1.11.1.js" data-content-id="sb"></script>
    <script src="/diyUpload/js/webuploader.html5only.min.js"></script>
    <script src="/diyUpload/js/diyUpload.js"></script>

    <style>
        #demo{ margin:0px; width:570px;height: auto; background:#0061AC;}
    </style>
</head>
<body>
<div id = "demo">
<?php
$nameAll = explode(",",$name);
if($name&&!empty($nameAll)){
    foreach($nameAll as $nn){
        echo "<div style='display: inline-block; margin:10px;width:170px;height:150px;padding:15px;float:left;position:relative; '><img src = '/images/close.png' class='closebutton'/><img width='170' height='150' src = '".trim($nn,"'")."'/></div>";
    }
}

?>
</div>
<style type="text/css">
    .closebutton{position:absolute; top:0; right:0; z-index:99;cursor: pointer}
</style>
<script type="text/javascript">
    var name  = "<?php echo $name;?>";
    var typeName = "<?php echo $typeName;?>";
$(document).ready(function(){
    $(".closebutton").click(
        function(){
            name = name.replace($(this).next('img').attr('src'),"");
            name = name.replace(",''","");
            name = name.replace("'',","");
            name = name.replace("''","");
            $("input[name='" + typeName + "']").val(name);
            $(this).parent('div').remove();
        }
    );
});
</script>
</body>
</html>
