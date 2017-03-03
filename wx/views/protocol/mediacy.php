<?php
if ($data['category'] == 1) {
    if ($data['uid'] == $user['id']) {
        echo $this->render('mediacyfinancing',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    } else {
        echo $this->render('mediacyinvestment',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    }
} elseif ($data['category'] == 2) {
    if ($data['uid'] == $user['id']) {
        echo $this->render('mediacyentrust',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    } else {
        echo $this->render('mediacycollection',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    }
} elseif ($data['category'] == 3) {
    if ($data['uid'] == $user['id']) {
        echo $this->render('mediacylawentrust',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    } else {
        echo $this->render('mediacylawer',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    }
}
?>