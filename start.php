<?php

define('AUTH_TOKEN','4a11e5b83c27d53a-f55357eaca14220c-400e6bedd901fe9');
define('HTTPHEADER', array(
    "Cache-control: no-cache",
    "Content-Type: application/JSON",
    "X-Viber-Auth-Token: 4a11e5b83c27d53a-f55357eaca14220c-400e6bedd901fe9"));
define('POSTFIELDS', "{ \r\n \"url\": \"https://vkassist.ru/viber/bot.php\",\r\n \"event_types\":[\r\n \"conversation_started\"\r\n ]\r\n}");
define('SET_WEBHOOK_URL', 'https://chatapi.viber.com/pa/set_webhook');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$curl = curl_init();
curl_setopt_array($curl,array(
    CURLOPT_URL => SET_WEBHOOK_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => POSTFIELDS,
    CURLOPT_HTTPHEADER => HTTPHEADER
));
$response = curl_exec($curl);
$err = curl_error($curl);
 
curl_close($curl);
 
if ($err) {
echo "cURL Error #:" . $err;
} else {
echo $response;
}
        