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

    # when user join our left bot send Message
    if (($join_member || $left_member) && $settingCursor->getLogService($chat_id)->log_service) {
        if ($join_member) {
            $bot->debug("`System Log:`\n\n" . "کاربر **{$new_member_name}** به گروه {$group_name} پیوست.");
        }
        if ($left_member) {
            $bot->debug("`System Log:`\n\n" . "کاربر **{$left_member_name}** از گروه {$group_name} خارج شد.");
        }
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
        $userCursor->newWarn($r_from_id, $chat_id, $first_name);
        $warn = $userCursor->getUser($r_from_id, $r_chat_id)->warn;

        $botMessage = "
کاربر〔 {$r_first_name} 〕

✦ - یک اخطار توسط ناظر برای شما ثبت شد
✘ - تعداد اخطار های شما : {$warn}/3
                ";
        $bot->sendMessage($chat_id, $botMessage);

        if ($warn == 3) {
            $userCursor->muteUser($r_from_id, $chat_id, $r_first_name);
            $bot->sendMessage($chat_id, "{$r_first_name} به دلیل 3 اخطار میوت شدی");
        }
    }
    die;
}

# delete warn user in group
if ($text == 'حذف اخطار') {
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        $userCursor->delWarn($r_from_id, $chat_id, $r_first_name);
        $warn = $userCursor->getUser($r_from_id, $r_chat_id)->warn;

        $botMessage = "
کاربر〔 {$r_first_name} 〕
        
✦ - اخطار های شما توسط ناظر حذف گردید
✘ - تعداد اخطار های شما : {$warn}/3
                        ";
        $bot->sendMessage($chat_id, $botMessage);
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
        $userCursor->muteUser($r_from_id, $r_chat_id, $r_first_name);
        $bot->sendMessage($r_chat_id, "⊛ - کاربر {$r_first_name} توسط ناظر گروه سکوت شد");
    }
    die;
}

# unmute user in group
if ($text == 'حذف سکوت') {
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        $userCursor->unMuteUser($r_from_id, $r_chat_id, $r_first_name);
        $bot->sendMessage($r_chat_id, "⊚ - کاربر {$r_first_name} توسط ناظر گروه رفع سکوت شد");
    }
    die;
}

# clean on join and left message
if ($text == 'قفل سرویس') {
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        if (!$settingCursor->getCleanServiceStat($chat_id)->clean_service) {
            $settingCursor->onCleanService($chat_id);
            $bot->sendMessage($chat_id, '✔ - قفل پیام ورود و خروج کاربران فعال شد');
        } else {
            $bot->sendMessage($chat_id, '• - قفل سرویس از قبل فعال میباشد');
        }
    }
    die;
}

# clean off join and left message
if ($text == 'حذف قفل سرویس') {
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        if ($settingCursor->getCleanServiceStat($chat_id)->clean_service) {
            $settingCursor->offCleanService($chat_id);
            $bot->sendMessage($chat_id, '✘ - قفل پیام ورود و خروج کاربران غیرفعال شد');
        } else {
            $bot->sendMessage($chat_id, '• - قفل سرویس از قبل غیرفعال میباشد');
        }
    }
    die;
}

if ($text == 'لاگ روشن') {
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        if (!$settingCursor->getLogService($chat_id)->log_service) {
            $settingCursor->onLogService($chat_id);
            $bot->sendMessage($chat_id, '※ - لاگ سیستم فعال شد، ورود و خروج کاربران گروه به اطلاع شما خواهد رسید');
        } else {
            $bot->sendMessage($chat_id, '☺ - لاگ سیستم از قبل فعال میباشد');
        }
    }
    die;
}

# clean off join and left message
if ($text == 'لاگ خاموش') {
    if ($userCursor->getUser($from_id, $chat_id)->is_admin || $userCursor->getUser($from_id, $chat_id)->is_creator) {
        if ($settingCursor->getLogService($chat_id)->log_service) {
            $settingCursor->offLogService($chat_id);
            $bot->sendMessage($chat_id, '※ - لاگ سیستم غیرفعال شد، ورود و خروج کاربران اطلاع رسانی نخواهد شد');
        } else {
            $bot->sendMessage($chat_id, '☺ - لاگ سیستم غیرفعال میباشد');
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
                        $userCursor->setNewAdmin($admin->user->id, $chat_id, $admin->user->first_name);
                        $botMessage .= "{$admin->user->first_name}\n";
                    }
                    if ($admin->status == 'creator') {
                        $userCursor->setCreator($admin->user->id, $chat_id, $admin->user->first_name);
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
    $bot->deleteMessages($chat_id, $r_message_id);
    if ($r_from_id) {
        $getUserInfo = $userCursor->getUser($r_from_id, $r_chat_id);
        $botMessage = "نام کاربری: {$first_name}\nشناسه عددی: {$getUserInfo->chat_id}\nتعداد پیام ها: {$getUserInfo->counter}\nتعداد اخطارها: {$getUserInfo->warn}";
        $bot->sendMessage($chat_id, $botMessage);
    } else {
        $getUserInfo = $userCursor->getUser($from_id, $chat_id);
        $botMessage = "نام کاربری شما: {$r_first_name}\nشناسه عددی شما: {$getUserInfo->chat_id}\nتعداد پیام ها: {$getUserInfo->counter}\nتعداد اخطارها: {$getUserInfo->warn}";
        $bot->sendMessage($chat_id, $botMessage);
    }
    die;
}

# when user send bot command, bot send status
if ($text == 'ping') {
    $bot->sendMessage($chat_id, "▾ `bot is online!` ▾\n\n*- Developers⤦*\n@DevSector\n@alirezaSDTD\n\n*- Hosting⤦*\nhttps://aranserver.com\n\n✦ Powered by *PHP* ✦");
    die;
}
