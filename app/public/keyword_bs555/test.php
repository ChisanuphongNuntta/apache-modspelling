<?php
// Replace [API_KEY] with your actual API key from Keyword Tool.
$apiKey = '3fa937b11edfd50c8b17863fd500ae22e88bd0de';
$keyword = urlencode('เสื้อยืดผู้ชาย'); // URL-encoded version of 'เสื้อยืดผู้ชาย'.

// Set the date range for the year 2022.
$startDate = '2022-01-01';
$endDate = '2022-12-31';

// Construct the API request URL.
$apiUrl = "https://api.keywordtool.io/v2/search/volume/google?apikey={$apiKey}&keyword=[\"{$keyword}\"]&time={$startDate},{$endDate}&output=json";

// Output the constructed URL.
echo $apiUrl;
?>