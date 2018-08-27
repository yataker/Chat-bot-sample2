<?php

define('TOKEN', '...');

if (file_exists(DEBUG)) unlink(DEBUG);

function debug($title, $text) {
	file_put_contents(DEBUG, '['.$title.']'."\n".$text."\n\n", FILE_APPEND);
}

function post($url, $object) {
	$json=json_encode($object);
	debug('output', $json);

	$curl=curl_init('https://api.line.me/v2/bot/message/'.$url);
	curl_setopt($curl, CURLOPT_POST, TRUE);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
	curl_setopt($curl, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Authorization: Bearer '.TOKEN
	]);

	$result=curl_exec($curl);
	debug('result', $result);

	curl_close($curl);
}

function reply($event, $text) {
	$object=[
		'replyToken'=>$event->replyToken, 
		'messages'=>[['type'=>'text', 'text'=>$text]]
	];
	post('reply', $object);
}

function reply_image($event, $original, $preview) {
	$object=[
		'replyToken'=>$event->replyToken, 
		'messages'=>[[
			'type'=>'image', 
			'originalContentUrl'=>$original, 
			'previewImageUrl'=>$preview
		]]
	];
	post('reply', $object);
}

function push($to, $text) {
	$object=[
		'to'=>$to, 
		'messages'=>[['type'=>'text', 'text'=>$text]]
	];
	post('push', $object);
}

function load($file) {
	$json=file_get_contents($file);
	return json_decode($json);
}

function save($file, $object) {
	$json=json_encode($object);
	file_put_contents($file, $json);
}

function lock($file) {
	$fp=fopen($file, 'c');
	flock($fp, LOCK_EX);
	return $fp;
}

function unlock($fp) {
	flock($fp, LOCK_UN);
	fclose($fp);
}
