<?php

class UserConnection extends Connection
{
    # add new user to Database
    public function addNewUser($chat_id, $group_id, $first_name)
    {
        $stmt = $this->db->prepare("INSERT INTO `tb_userStat` (`chat_id`, `group_id`, `first_name`) VALUES (?, ?, ?)");
        $stmt->execute([$chat_id, $group_id, $first_name]);
    }

    # get user info from Database
    public function getUser($chat_id, $group_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM `tb_userStat` WHERE `chat_id` = ? AND `group_id` = ? ");
        $stmt->execute([$chat_id, $group_id]);
        return $stmt->fetch();
    }

    # add 1 counter to message count for user
    public function addCountMessage($chat_id, $group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_userStat` SET `counter` = `counter` + 1 WHERE `chat_id` = ? AND `group_id` = ?");
        $stmt->execute([$chat_id, $group_id]);
    }

    public function newWarn($chat_id, $group_id, $first_name)
    {
        if (!$this->getUser($chat_id, $group_id)) {
            $this->addNewUser($chat_id, $group_id, $first_name);
        }
        $stmt = $this->db->prepare("UPDATE `tb_userStat` SET `warn` = `warn` + 1 WHERE `chat_id` = ? AND `group_id` = ?");
        $stmt->execute([$chat_id, $group_id]);
    }

    public function delWarn($chat_id, $group_id, $first_name)
    {
        if (!$this->getUser($chat_id, $group_id)) {
            $this->addNewUser($chat_id, $group_id, $first_name);
        }
        $stmt = $this->db->prepare("UPDATE `tb_userStat` SET `warn` = 0 WHERE `chat_id` = ? AND `group_id` = ?");
        $stmt->execute([$chat_id, $group_id]);
    }

    public function muteUser($chat_id, $group_id, $first_name)
    {
        if (!$this->getUser($chat_id, $group_id)) {
            $this->addNewUser($chat_id, $group_id, $first_name);
        }
        $stmt = $this->db->prepare("UPDATE `tb_userStat` SET `is_mute` = 1 WHERE `chat_id` = ? AND `group_id` = ?");
        $stmt->execute([$chat_id, $group_id]);
    }

    public function unMuteUser($chat_id, $group_id, $first_name)
    {
        if (!$this->getUser($chat_id, $group_id)) {
            $this->addNewUser($chat_id, $group_id, $first_name);
        }
        $stmt = $this->db->prepare("UPDATE `tb_userStat` SET `is_mute` = 0 WHERE `chat_id` = ? AND `group_id` = ?");
        $stmt->execute([$chat_id, $group_id]);
    }

    public function setCreator($chat_id, $group_id, $first_name)
    {
        if (!$this->getUser($chat_id, $group_id)) {
            $this->addNewUser($chat_id, $group_id, $first_name);
        }
        $stmt = $this->db->prepare("UPDATE `tb_userStat` SET `is_creator` = 1 WHERE `chat_id` = ? AND `group_id` = ?");
        $stmt->execute([$chat_id, $group_id]);
    }

    public function setNewAdmin($chat_id, $group_id, $first_name)
    {
        if (!$this->getUser($chat_id, $group_id)) {
            $this->addNewUser($chat_id, $group_id, $first_name);
        }
        $stmt = $this->db->prepare("UPDATE `tb_userStat` SET `is_admin` = 1 WHERE `chat_id` = ? AND `group_id` = ?");
        $stmt->execute([$chat_id, $group_id]);
    }
    public function removeNewAdmin($chat_id, $group_id, $first_name)
    {
        if (!$this->getUser($chat_id, $group_id)) {
            $this->addNewUser($chat_id, $group_id, $first_name);
        }
        $stmt = $this->db->prepare("UPDATE `tb_userStat` SET `is_admin` = 0 WHERE `chat_id` = ? AND `group_id` = ?");
        $stmt->execute([$chat_id, $group_id]);
    }

    public function addPoint($from_id, $group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_userStat` SET `point` = `point` + 1 WHERE `chat_id` = ? AND `group_id` = ? ");
        $stmt->execute([$from_id, $group_id]);
    }

    public function setLevel($from_id, $group_id, $level)
    {
        $stmt = $this->db->prepare("UPDATE `tb_userStat` SET `level` = ? WHERE `chat_id` = ? AND `group_id` = ? ");
        $stmt->execute([$level, $from_id, $group_id]);
    }
}
