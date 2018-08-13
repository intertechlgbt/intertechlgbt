#!/usr/bin/env php
<?php 
date_default_timezone_set('UTC');

$root = __DIR__.'/..';

$xml = simplexml_load_string(file_get_contents('https://interests.me/org/intertechlgbt/rss'));
// $xml = simplexml_load_string(file_get_contents(__DIR__.'/sample.xml'));

foreach ($xml->channel->item as $item) {
	$title   = $item->title;
	$slug    = slugify($title);
	$date    = date('Y-m-d', strtotime($item->pubDate));
	$content = $item->description;

	printf('Importing "%s"...', $title);

	$html = file_get_contents($item->link.'/amp');

	if (!preg_match('~<script type="application/ld\+json">(?P<script>.+?)</script>~is', $html, $matches)) {
		throw new Exception('Could not find story structured data');
	}

	$json = json_decode(trim($matches['script']), true);

	if (
		!empty($json['about']) &&
		$json['about']['@type'] == 'Event'
	) {
		$json = $json['about'];

		$category = 'events';

		$register  = $json['url'];
		$eventDate = date('Y/m/d', strtotime($json['startDate']));
		$startDate = date('Y-m-d H:i:s', strtotime($json['startDate']));
		$endDate   = date('Y-m-d H:i:s', strtotime($json['endDate']));
		$company   = $json['location']['name'];
		$address   = array_filter((array)$json['location']['address']);
		$imageUrl  = $date.'-'.$slug.'.'.pathinfo($json['image']['url'], PATHINFO_EXTENSION);

		unset($address['@type']);
		$location = implode(', ', array_filter($address));

		$tags = [$company];
		$tags = json_encode(array_filter($tags));

		copy($json['image']['url'], $root.'/assets/images/events/'.$imageUrl);

		@mkdir($dir = $root.'/_drafts/'.$category, 0777, true);

		file_put_contents($dir.'/'.$date.'-'.$slug.'.md', "---
layout: 	event
permalink:	/events/$eventDate/$slug
title:		\"$title\"
company:	\"$company\"
date:		$date
starts:		$startDate
ends: 		$endDate
location:	\"$location\"
register:	$register
image: 		$imageUrl
category:	events
tags:		$tags
---

$content");

		printf(' done.'.PHP_EOL);

	} elseif (
		!empty($json['image'])
	) {
		$category = 'news';

		$imageUrl = $date.'-'.$slug.'.'.pathinfo($json['image']['url'], PATHINFO_EXTENSION);

		$tags = [$company];
		$tags = json_encode(array_filter($tags));

		copy($json['image']['url'], $root.'/assets/images/news/'.$imageUrl);

		@mkdir($dir = $root.'/_drafts/'.$category, 0777, true);

		file_put_contents($dir.'/'.$date.'-'.$slug.'.md', "---
layout: 	news
permalink:	/news/$eventDate/$slug
title:		\"$title\"
date:		$date
image: 		$imageUrl
category:	news
tags:		$tags
---

$content");

	} else {
		printf(' failed.'.PHP_EOL);
	}
}

function slugify($string) {
	$replace = [
		'intertech' => '',
	];

	$string = preg_replace('~[^\pL\d]+~u', '-', $string);
	$string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
	$string = strtolower($string);
	$string = str_replace(array_keys($replace), array_values($replace), $string);
	$string = preg_replace('~[^-\w]+~', '', $string);
	$string = trim($string, '-');
	$string = preg_replace('~-+~', '-', $string);

	return $string;
}