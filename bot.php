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

    if ($userCursor->getUser($from_id, $chat_id)->is_mute) {
        $bot->deleteMessages($chat_id, $message_id);
    }
}

# show user info when send /me in group
if ($text == '/me') {
    $getUserInfo = $userCursor->getUser($from_id, $chat_id);
    $botMessage = "نام کاربری شما: {$getUserInfo->first_name}\nشناسه عددی شما: {$getUserInfo->chat_id}\nتعداد پیام ها: {$getUserInfo->counter}\nامتیاز شما: {$getUserInfo->point}\nسطح شما: {$getUserInfo->level}";
    $bot->sendMessage($chat_id, $botMessage);
    die;
}

if ($text == 'اخطار') {
    $userCursor->newWarn($r_from_id, $chat_id);
    $warn = $userCursor->getUser($r_from_id, $chat_id)->warn;
    $bot->sendMessage($chat_id, "{$r_first_name} اخطار گرفتی\nتعداد اخطار: {$warn}/3");

    if ($warn == 3) {
        $userCursor->muteUser($r_from_id, $chat_id);
        $bot->sendMessage($chat_id, "{$r_first_name} به دلیل 3 اخطار میوت شدی");
    }
    die;
}

if ($text == 'سکوت') {
    $userCursor->muteUser($r_from_id, $chat_id);
    $bot->sendMessage($chat_id, "کاربر {$r_first_name} توسط ناظر سکوت شد");
    die;
}

if ($text == 'رفع سکوت') {
    $userCursor->unmuteUser($r_from_id, $chat_id);
    $bot->sendMessage($chat_id, "کاربر {$r_first_name} توسط ناظر رفع سکوت شد");
    die;
}

if ($text == 'پیکربندی') {
    $getChatAdmins = $bot->getChatAdmins($chat_id)->result;
    $botMessage = "پیکربندی انجام شد\nادمین های شناسایی شده: \n\n";
    foreach ($getChatAdmins as $admin) {
        if ($admin->status != 'creator') {
            $userCursor->setNewAdmin($admin->user->id, $chat_id);
            $botMessage .= "{$admin->user->first_name}\n";
        }
        if ($admin->status == 'creator') {
            $userCursor->setCreator($admin->user->id, $chat_id);
        }
    }
    $bot->sendMessage($chat_id, $botMessage);
    die;
}