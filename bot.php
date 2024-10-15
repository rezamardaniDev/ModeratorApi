<?php

# <--------------- get update from telegram --------------- > #
$update = json_decode(file_get_contents('php://input'));
# <--------------- include other module --------------- > #
include 'config/config.php';
include 'utils/methods.php';
include 'utils/helpers.php';
include 'utils/variable.php';
include 'database/userConnection.php';
# <--------------- create new object from modules --------------- > #
$bot = new Bot($token);
$userCursor = new UserConnection();
# <--------------- main structure --------------- > #
if ($update) {

    # clean join message
    if ($join_member) {
        $bot->deleteMessages($chat_id, $message_id);
        die;
    }

    # clean left message
    if ($left_member) {
        $bot->deleteMessages($chat_id, $message_id);
        die;
    }

    # check exist user
    $userExists = $userCursor->getUser($from_id, $chat_id);
    if ($userExists) {
        # just add 1 counter if user exist
        $userCursor->addCountMessage($from_id, $chat_id);
    } else {
        # add new user and add 1 counter
        $userCursor->addNewUser($from_id, $chat_id, $first_name);
        $userCursor->addCountMessage($from_id, $chat_id);
    }
}

# show user info when send /me in group
if ($text == '/me') {
    $getUserInfo = $userCursor->getUser($from_id, $chat_id);
    $botMessage = "نام کاربری شما: {$getUserInfo->first_name}\nشناسه عددی شما: {$getUserInfo->chat_id}\nتعداد پیام ها: {$getUserInfo->counter}\nامتیاز شما: {$getUserInfo->point}\nسطح شما: {$getUserInfo->level}";
    $bot->sendMessage($chat_id, $botMessage);
    die;
}
