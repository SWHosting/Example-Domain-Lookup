<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASEURL','https://ote-api.swhosting.com/v1/');
define('TOKEN','YOUR-BEARER-TOKEN');

// GET /domains/{domain}/available
function available($domain = null) {

    if (is_null($domain) || trim($domain) == '') {
        throw new Exception("Domain is required");
    }

    $url = BASEURL.'domains/'.$domain.'/available';
    return makeRequest($url);
}

function makeRequest($url, $method = 'GET') {

    $options = array(
      'http' => array(
        'method' => $method,
        'header' => "Authorization: Bearer ".TOKEN."\r\n".
                    "Content-Type: application/json"
      )
    );
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        throw new Exception("Bad response");
    }

    return $response;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <head>
        <title>Example of Domain Lookup - SW Hosting</title>
        <style>
            .available { color: green; }
            .notavailable { color: red; }
        </style>
    </head>
    <body>
        <h1>Domain lookup</h1>
        <form method="GET">
            <input type="text" name="domain">
            <button type="submit">Search</button>
        </form>

        <div class="result">
        <?php
        if (isset($_GET['domain'])) {
            try {
                $domain = $_GET['domain'];
                $response = json_decode(available($domain));

                if ($response->available) {
                    $result = '<p class="available">Congratulations, the domain '.$response->domain.' is available! <a href="#">Register now</a></p>';
                } else {
                    $result = '<p class="notavailable">Sorry, the domain '.$response->domain.' is not available</p>';
                }

                echo $result;

            } catch (Exception $e) {
                echo 'ERROR: '.$e->getMessage();
            }
        }
        ?>
        </div>
    </body>
</html>
