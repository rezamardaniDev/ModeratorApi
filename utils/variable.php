<?php

if (isset($update->message)) {
    $message = $update->message;
    $text    = $message->text;
    $from_id = $message->from->id;
    $first_name = $message->from->first_name;
    $chat_id = $message->chat->id;
    $first_name = $message->from->first_name;
    $message_id  = $update->message->message_id;
    $join_member = $message->new_chat_participant;
    $left_member = $message->left_chat_participant;
    $participant_id = $join_member->id;
    $participant_first_name = $join_member->first_name;
}


if (isset($update->message->reply_to_message)) {
    $message = $update->message->reply_to_message;
    $r_text    = $message->text;
    $r_from_id = $message->from->id;
    $r_chat_id = $message->chat->id;
    $r_first_name = $message->from->first_name;
    $r_message_id  = $update->message->message_id;
    $r_join_member = $message->new_chat_participant;
    $r_left_member = $message->left_chat_participant;
}

if (isset($update->callback_query)) {
    $callback_id = $update->callback_query->id;
    $from_id     = $update->callback_query->from->id;
    $data        = $update->callback_query->data;
    $query_id    = $update->callback_query->id;
    $type        = $update->callback_query->message->chat->type;
    $message_id  = $update->callback_query->message->message_id;
}
