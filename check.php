<?php
// Get the URL to fetch from the query parameters or POST data
$url = $_REQUEST['url'];

// Check if the URL is provided and is a valid URL
if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['result' => 'Not available', 'url' => $url]);
    exit;
}

// Initialize cURL
$curlInit = curl_init($url);

// Set options
curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($curlInit, CURLOPT_HEADER, true);
curl_setopt($curlInit, CURLOPT_NOBODY, true);
curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

// Get response
$response = curl_exec($curlInit);

// Check for cURL errors
if (curl_errno($curlInit)) {
    header('HTTP/1.1 200 OK'); // Even if there is an error, we still return 200 OK status
    echo json_encode(['result' => 'Not available', 'url' => $url]);
} else {
    // Check if the website is available (status code 2xx or 3xx)
    $statusCode = curl_getinfo($curlInit, CURLINFO_HTTP_CODE);
    if ($statusCode >= 200 && $statusCode < 400) {
        header('HTTP/1.1 200 OK');
        echo json_encode(['result' => 'Available', 'url' => $url]);
    } else {
        header('HTTP/1.1 200 OK');
        echo json_encode(['result' => 'Not available', 'url' => $url]);
    }
}

// Close the cURL session
curl_close($curlInit);
?>
