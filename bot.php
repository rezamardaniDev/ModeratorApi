<?php

# <--------------- get update from telegram --------------- > #
$update = json_decode(file_get_contents('php://input'));
# <--------------- include other module --------------- > #
include 'config/config.php';
include 'utils/methods.php';
include 'utils/variable.php';
include 'database/userConnection.php';
# <--------------- create new object from modules --------------- > #
$bot = new Bot($token);
$userCursor = new UserConnection();
# <--------------- main structure --------------- > #
if ($join_member) {
    $bot->deleteMessages($chat_id, $message_id);
    die;
}

if ($left_member) {
    $bot->deleteMessages($chat_id, $message_id);
    die;
}

if ($text == 'سلام') {
    $userCursor->addNewUser(541255, $chat_id, 'sara');
    $bot->sendMessage($chat_id, 'ثبت نام شما انجام شد');
    die;
}
