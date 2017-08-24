<?php
include "lib/config.php";
include "lib/vendor/autoload.php";
/*
$rating = new \GoFetchCode\Search\Rating;
$rating->user_id = 10;
$rating->rating = 5;
$rating->search_string = "Hello World";
$rating->created = "2007-08-19 10:23:54";

var_dump($rating->save());*/

$rating = \GoFetchCode\Search\Rating::findBySearchString(12, "Test");

echo "Star rating of ".$rating->rating;
