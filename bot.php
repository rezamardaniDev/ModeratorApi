<?php

# <--------------- get update from telegram --------------- > #
$update = json_decode(file_get_contents('php://input'));
# <--------------- require other module --------------- > #
require 'config/config.php';
require 'utils/methods.php';
require 'utils/helpers.php';
require 'utils/variable.php';
require 'database/connector.php';
require 'database/settingsMethods.php';
require 'database/usersMethods.php';
require 'database/groupsMethods.php';
# <--------------- create new object from modules --------------- > #
$bot = new Bot($token);
$userCursor = new UserConnection();
$settingCursor = new SettingConnection();
$groupCursor = new GroupConnection();
# <--------------- main structure --------------- > #
if ($update && $settingCursor->getSetting($chat_id)) {
    # clean user message is user muted
    if ($userCursor->getUser($from_id, $chat_id)->is_mute) {
        $bot->deleteMessages($chat_id, $message_id);
        die;
    }

    # clean join and left message
    if (($join_member || $left_member) && $settingCursor->getCleanServiceStat($chat_id)->clean_service) {
        $bot->deleteMessages($chat_id, $message_id);
    }

    # when new member join group, added to database
    if ($participant_id) {
        $userExists = $userCursor->getUser($participant_id, $chat_id);
        if (!$userExists) {
            $userCursor->addNewUser($participant_id, $chat_id, $participant_first_name);
            $userCursor->addCountMessage($participant_id, $chat_id);
        }
    }

    # check exist user when send message in group
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

# warn user in group
if ($text == 'اخطار') {
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        if ($userCursor->getUser($r_from_id, $r_chat_id)->is_creator) {
            $bot->deleteMessages($chat_id, $message_id);
            die;
        }
        $userCursor->newWarn($r_from_id, $chat_id , $first_name);
        $warn = $userCursor->getUser($r_from_id, $r_chat_id)->warn;
        $bot->sendMessage($chat_id, "{$r_first_name} اخطار گرفتی\nتعداد اخطار: {$warn}/3");

        if ($warn == 3) {
            $userCursor->muteUser($r_from_id, $chat_id , $first_name);
            $bot->sendMessage($chat_id, "{$r_first_name} به دلیل 3 اخطار میوت شدی");
        }
    }
    die;
}

# delete warn user in group
if ($text == 'حذف اخطار') {
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        $userCursor->delWarn($r_from_id, $chat_id , $first_name);
        $bot->sendMessage($chat_id, "{$r_first_name} عزیز\n اخطار های شما صفر شد جووووون");
    }
    die;
}

# mute user in group
if ($text == 'سکوت') {
    
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        if ($userCursor->getUser($r_from_id, $r_chat_id)->is_creator) {
            $bot->deleteMessages($chat_id, $message_id);
            die;
        }
        $userCursor->muteUser($r_from_id, $r_chat_id , $first_name);
        $bot->sendMessage($r_chat_id, "کاربر {$r_first_name} توسط ناظر سکوت شد");
    }
    die;
}

# unmute user in group
if ($text == 'حذف سکوت') {
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        $userCursor->unMuteUser($r_from_id, $r_chat_id , $r_first_name);
        $bot->sendMessage($r_chat_id, "کاربر {$r_first_name} توسط ناظر رفع سکوت شد");
    }
    die;
}

# clean on join and left message
if ($text == 'قفل سرویس') {
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        if (!$settingCursor->getCleanServiceStat($chat_id)->clean_service) {
            $settingCursor->onCleanService($chat_id);
            $bot->sendMessage($chat_id, 'قفل پیام های ورود و خروج کاربران فعال شد.');
        } else {
            $bot->sendMessage($chat_id, 'قفل سرویس از قبل فعال میباشد');
        }
    }
    die;
}

# clean off join and left message
if ($text == 'حذف قفل سرویس') {
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        if ($settingCursor->getCleanServiceStat($chat_id)->clean_service) {
            $settingCursor->offCleanService($chat_id);
            $bot->sendMessage($chat_id, 'قفل پیام های ورود و خروج کاربران غیرفعال شد.');
        } else {
            $bot->sendMessage($chat_id, 'قفل سرویس از قبل غیرفعال میباشد');
        }
    }
    die;
}

# configuration bot in gorup when send config command
if ($text == 'پیکربندی') {
    foreach ($bot->getChatAdmins($chat_id)->result as $admin) {
        if ($admin->user->id == $from_id && $admin->status == "creator") {

            $checkExistsGroup = $settingCursor->getSetting($chat_id);
            if (!$checkExistsGroup) {

                $settingCursor->addNewSetting($chat_id, $group_name);
                $groupCursor->addNewGroup($chat_id, $group_name);
                $getChatAdmins = $bot->getChatAdmins($chat_id)->result;

                $botMessage = "پیکربندی انجام شد\nادمین های شناسایی شده: \n\n";

                foreach ($getChatAdmins as $admin) {
                    $userExists = $userCursor->getUser($admin->user->id, $chat_id);
                    if (!$userExists) {
                        $userCursor->addNewUser($admin->user->id, $chat_id, $admin->user->first_name);
                    }

                    if ($admin->status != 'creator') {
                        $userCursor->setNewAdmin($admin->user->id, $chat_id , $admin->user->first_name);
                        $botMessage .= "{$admin->user->first_name}\n";
                    }
                    if ($admin->status == 'creator') {
                        $userCursor->setCreator($admin->user->id, $chat_id , $admin->user->first_name);
                        $botMessage .= "{$admin->user->first_name}\n";
                    }
                }
                $bot->sendMessage($chat_id, $botMessage);
            }
            die;
        }
    };
    die;
}

# show user info when send /me in group
if ($text == '/me') {
    $getUserInfo = $userCursor->getUser($from_id, $chat_id);
    $botMessage = "نام کاربری شما: {$getUserInfo->first_name}\nشناسه عددی شما: {$getUserInfo->chat_id}\nتعداد پیام ها: {$getUserInfo->counter}\nامتیاز شما: {$getUserInfo->point}\nسطح شما: {$getUserInfo->level}";
    $bot->sendMessage($chat_id, $botMessage);
    die;
}

# when user send bot command, bot send status
if ($text == 'ربات') {
    $bot->sendMessage($chat_id, 'bot is online!');
    die;
}
