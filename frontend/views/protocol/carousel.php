<?php
if ($type == 1) {
    echo $this->render('carousel1');
} elseif ($type == 2) {
    echo $this->render('carousel2');
} elseif ($type == 3) {
    echo $this->render('carousel3');
}elseif($type == 4){
    echo $this->render('carousel4');
}elseif($type == 5){
    echo $this->render('carousel5');
}
?>