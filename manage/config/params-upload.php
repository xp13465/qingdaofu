<?php
return [
    'uploadRule' => [
		[['file'], 'file'],
		[['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpeg,jpg,bmp,gif' , 'maxSize'=>1024*1024*10],
	],
	'uploadPath'=>[
		'file'=>[
			'savePath' => 'uploads/',
			'linkPath' => '/uploads/',
		],
		'imageFile'=>[
			'savePath' => 'uploads/images/',
			'linkPath' => '/uploads/images/',
		],
	],
];
