<?php
if ($data['category'] == 1) {
    if ($data['uid'] == $user['id']) {
        echo $this->render('mediacyfinancings',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    } else {
        echo $this->render('mediacyinvestments',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    }
} elseif ($data['category'] == 2) {
    if ($data['uid'] == $user['id']) {
        echo $this->render('mediacyentrusts',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    } else {
        echo $this->render('mediacycollections',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    }
} elseif ($data['category'] == 3) {
    if ($data['uid'] == $user['id']) {
        echo $this->render('mediacylawentrusts',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    } else {
        echo $this->render('mediacylawers',['certi'=>$certi,'data'=>$data,'desca'=>$desca,'user'=>$user]);
    }
}
?>