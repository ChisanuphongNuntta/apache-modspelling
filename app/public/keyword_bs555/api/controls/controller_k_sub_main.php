<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/k_sub_main.php';

$database = new Database();
$db = $database->getConnection();
$ts_k_sub_main = new K_sub_main($db);

$ts_k_sub_main->kid = $_POST['kid'];

$stmt = $ts_k_sub_main->get_all_k_sub_main();
$num = $stmt->rowCount();

if ($num > 0) {
	$i=0;
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$rst_item[$i] = $keyword;
		$i++;
		//   array_push($rst_arr, $rst_item);
	}//end while
	http_response_code(200);
	// echo json_encode($rst_item);
	$ts_k_sub_main->count_in_gk = $i;
	$stmt = $ts_k_sub_main->update_k_sub_main();
}//end if check num > 0

// Your API key
$apiKey = '3fa937b11edfd50c8b17863fd500ae22e88bd0de';
// echo $rst_item;
// The keyword you want to search for
$keywords = $rst_item; 

// JSON encode the array of keywords
$jsonKeywords = json_encode($keywords);

// URL encode the JSON string of keywords
$encodedKeywords = urlencode($jsonKeywords);

// The API endpoint with the encoded keyword and your API key
$apiUrl = "https://api.keywordtool.io/v2/search/volume/google?apikey=$apiKey&keyword=$encodedKeywords&output=json";

// Initialize a cURL session
$curl = curl_init();

// Set the cURL options
curl_setopt($curl, CURLOPT_URL, $apiUrl);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);

// Execute the cURL session
$response = curl_exec($curl);

// Close the cURL session
curl_close($curl);

// Decode the JSON response
$data = json_decode($response, true);

// Check if the data was retrieved successfully
if ($data) {
	// Do something with the $data
	// print_r($data);
	$tableData = array_values($data['results']);

    // Format the data as needed for Bootstrap Table
    // This is an example and may need to be adjusted based on the actual structure of your data
    $formattedData = array_map(function($item) {
        return [
            'keyword' => $item['string'],
            'search_avg' => $item['volume'],
            'cerrent_search_month' => $item['m1'],
            'cpc' => $item['cpc'],
            'cmp' => $item['cmp'],
            // Add other fields as needed
        ];
    }, $tableData);

    // Output the formatted data as a JSON string
    echo json_encode($formattedData);
} else {
	echo "Error retrieving data.";
}

//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////




?>