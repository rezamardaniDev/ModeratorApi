<?php

# <--------------- get update from telegram --------------- > #
$update = json_decode(file_get_contents('php://input'));
# <--------------- include other module --------------- > #
require 'config/config.php';
require 'utils/methods.php';
require 'utils/variable.php';

# <--------------- create new object from modules --------------- > #
$bot = new Bot($token);
# <--------------- main structure --------------- > #
if ($join_member) {
    $bot->deleteMessages($chat_id, $message_id);
    die;
}

if ($left_member) {
    $bot->deleteMessages($chat_id, $message_id);
    die;
}
