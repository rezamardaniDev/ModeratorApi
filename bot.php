<?php

$update = json_decode(file_get_contents('php://input'));
include 'config/config.php';
include 'utils/methods.php';
include 'utils/variable.php';

$bot = new Bot($token);

if ($update) {
    $bot->sendMessage($from_id, '/start');
    $bot->debug($update);
    die;
}
