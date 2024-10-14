<?php

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
