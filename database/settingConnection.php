<?php

require 'connector.php';

class SettingConnection extends Connection
{
    # add new user to Database
    public function addNewGroup($group_id)
    {
        $stmt = $this->db->prepare("INSERT INTO `tb_settings` (`group_id`) VALUES (?)");
        $stmt->execute([$group_id]);
    }

    # get user info from Database
    public function getCleanServiceStat($group_id)
    {
        $stmt = $this->db->prepare("SELECT `clean_service` FROM `tb_settings` WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
        return $stmt->fetch();
    }

    # add 1 counter to message count for user
    public function onCleanService($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_service` = 1 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }

    public function offCleanService($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_service` = 0 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }
}
