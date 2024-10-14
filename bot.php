<?php

$update = json_decode(file_get_contents('php://input'));
include 'config/config.php';
include 'utils/methods.php';
include 'utils/variable.php';

$bot = new Bot($token);

if ($update->message->new_chat_participant) {
    $bot->deleteMessages($chat_id, $update->message->message_id);
    die;
}

if ($update->message->left_chat_participant) {
    $bot->deleteMessages($chat_id, $update->message->message_id);
    die;
}
