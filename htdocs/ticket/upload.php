<?php

$size = $_FILES['data']['size']; //the size in bytes
$input = $_FILES['data']['tmp_name']; //temporary name that PHP gave to the uploaded file
//$output = $_FILES['whatsapp.wav']['name'].".wav"; //letting the client control the filename is a rather bad idea
$returnMessage = "file too long";

if ($size > 0 && $size<8*1024*1024) {
    $target_url = 'https://muso:depistageY*1@connector.musohealth.ml/erp-send/tel/' . $_POST['tel'];

    $post = array(
        'file' => curl_file_create($input)
    );

    $ch = curl_init($target_url);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $msg = curl_exec($ch);
    $info = curl_getinfo($ch);

    if ($info['http_code'] === 200) {
        $returnMessage = json_decode($msg, 1);
    } else {
        $returnMessage = 'error';
    }
    curl_close($ch);
}

    echo json_encode([
        "msg"=>$returnMessage
    ]);