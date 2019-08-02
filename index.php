<?php
define('HTTPHEADER', array(
    "Cache-control: no-cache",
    "Content-Type: application/JSON",
    "X-Viber-Auth-Token: 4a11e5b83c27d53a-f55357eaca14220c-400e6bedd901fe9"));
define('POSTFIELDS', "{ \r\n \"url\": \"https://vkassist.ru/viber\",\r\n \"event_types\":[\r\n \"conversation_started\"\r\n ]\r\n}");
define('SEND_MSG_URL', 'https://chatapi.viber.com/pa/send_message');
if(!isset($_REQUEST)):
    exit();
endif;
$viber = json_decode(file_get_contents("php://input"));

