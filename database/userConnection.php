<?php

require 'connector.php';

class UserConnection extends Connection
{
    public function addNewUser($group_id, $chat_id, $first_name)
    {
        $stmt = $this->db->prepare("INSERT INTO `tb_userStat` (`group_id`, `chat_id`, `first_name`) VALUES (?, ?, ?)");
        $stmt->execute([$group_id, $chat_id, $first_name]);
    }
}
