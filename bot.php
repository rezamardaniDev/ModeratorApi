<?php

$update = json_decode(file_get_contents('php://input'));
include 'config/config.php';
include 'utils/methods.php';
include 'utils/variable.php';

$bot = new Bot($token);

if ($update->message->text == '/start') {
    $bot->sendMessage($update->message->from->id, 'hello');
    die;
}
