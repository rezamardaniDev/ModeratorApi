<?php

# <--------------- get update from telegram --------------- > #
$update = json_decode(file_get_contents('php://input'));
# <--------------- include other module --------------- > #
include 'config/config.php';
include 'utils/methods.php';
include 'utils/helpers.php';
include 'database/userConnection.php';
# <--------------- create new object from modules --------------- > #
$bot = new Bot($token);
$userCursor = new UserConnection();
# <--------------- main structure --------------- > #
if (isset($update->message)) {
    $message = $update->message;
    $text    = $message->text;
    $from_id = $message->from->id;
    $chat_id = $message->chat->id;
    $message_id  = $update->message->message_id;
    $join_member = $message->new_chat_participant;
    $left_member = $message->left_chat_participant;
}

if (isset($update->callback_query)) {
    $callback_id = $update->callback_query->id;
    $from_id     = $update->callback_query->from->id;
    $data        = $update->callback_query->data;
    $query_id    = $update->callback_query->id;
    $type        = $update->callback_query->message->chat->type;
    $message_id  = $update->callback_query->message->message_id;
}

# <--------------- clean join and left message --------------- > #
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
        $userCursor->addNewUser( $from_id, $chat_id, 'as');
        $userCursor->addCountMessage($from_id, $chat_id);
    }
}

# show user info when send /me in group
if ($text == '/me') {
    $getUser = $userCursor->getUser($from_id, $chat_id);
    $text = "اسم : $getUser->first_name
    تعداد پیام : $getUser->counter
    امتیاز : $getUser->point
    سطح : $getUser->level
    ";
    $bot->debug($text);
    die;
}
