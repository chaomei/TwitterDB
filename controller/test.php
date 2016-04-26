<?php

include '../twitter_search/TwitterSearch.php';

$twitter = new TwitterSearch();

$since_id = 0;
$query = 'panama papers';
$twitter->simpleSearch($query, $since_id);