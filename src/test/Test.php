<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use WpApi\Api;

$api = new Api("");
/*
$opt = [
    'page'=> null,
    'limit'=>null,
    'phone'=>null,
    'status'=>null,
];
$messages = $api->messages($opt);
print_r(json_decode($messages));
*/
/*
$opt = [
    'page'=> null,
    'limit'=>null,
    'phone'=>null
];
$incoming_messages = $api->incoming_messages($opt);
print_r(json_decode($incoming_messages));
*/
/*
$show_message = $api->show_message("message_id");
print_r(json_decode($show_message));
*/
/*
$opt = [
    'message_body'=> "tester",
    'phone_numbers'=>["+905467751802"]
];
$send_message = $api->send_message($opt);
print_r(json_decode($send_message));
*/
/*
$opt = [
    'message_body'=> "tester",
    'phone_numbers'=>["+905467751802"],
    'file'=> dirname(__FILE__).'\test.jpg'
];
$send_message = $api->send_message($opt);
print_r(json_decode($send_message));
*/
/*
$opt = [
    'code'=> "0547882",
    'phone_number'=>"+905467751802",
];
$send_code = $api->send_code($opt);
print_r(json_decode($send_code));
*/