<?php

function bot($event) {
	$id='...';

	if (!preg_match('/価格/', $event->message->text)) return;
	$keyword=preg_replace('/価格/', '', $event->message->text);

	$url='https://app.rakuten.co.jp/services/api/IchibaItem/Search/20140222';
	$url.='?applicationId='.$id;
	$url.='&keyword='.urlencode($keyword);
	$url.='&sort='.urlencode('+itemPrice');
	$url.='&elements=itemName,itemPrice,itemUrl';
	$url.='&hits=3';
	debug('url', $url);

	$result=load($url);
	$text='「'.$keyword."」の検索結果です。\n\n";
	foreach ($result->Items as $item) {
		$text.=mb_substr($item->Item->itemName, 0, 40)."...\n";
		$text.=$item->Item->itemPrice."円\n";
		$text.=$item->Item->itemUrl."\n\n";
	}
	reply($event, $text);
}

