<?php


    if (isset($_SERVER['HTTP_ORIGIN'])) {
        //header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');    
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
    }   
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
    //echo "1";print_r($_POST['tally_server_ip']);die;
    //$array = $_POST;

    $data = array(
        'data' => $_POST['data'],
        'organizations' => $_POST['organizations'],
    );
    
    // Convert array to query string
    $post_fields = http_build_query($data);
    
    // Initialize curl
    $curl = curl_init();
    
    // Set curl options
    curl_setopt($curl, CURLOPT_URL, 'http://'.$_POST['tally_server_ip'].'/metricserp-tallyErp9/vendor/insertVendor.php');
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // Execute curl request
    $response = curl_exec($curl);
    // Close curl connection
    curl_close($curl);
    //curl_close($curl);
    echo $response;
    