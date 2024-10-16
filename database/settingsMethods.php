<?php

class SettingConnection extends Connection
{
    # add new user to Database
    public function addNewSetting($group_id, $group_name)
    {
        $stmt = $this->db->prepare("INSERT INTO `tb_settings` (`group_id`, `group_name`) VALUES (?, ?)");
        $stmt->execute([$group_id, $group_name]);
    }

    # get group info
    public function getSetting($group_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM `tb_settings` WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
        return $stmt->fetch();
    }



    # get cleanService info from Database
    public function getCleanServiceStat($group_id)
    {
        $stmt = $this->db->prepare("SELECT `clean_service` FROM `tb_settings` WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
        return $stmt->fetch();
    }

    # set 1 for cleanService in database
    public function onCleanService($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_service` = 1 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }

    # set 0 for cleanService in database
    public function offCleanService($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_service` = 0 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }



    # get cleanlink info from Database
    public function getCleanLinkStat($group_id)
    {
        $stmt = $this->db->prepare("SELECT `clean_link` FROM `tb_settings` WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
        return $stmt->fetch();
    }

    # set 1 for cleanLink in database
    public function onCleanLink($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_link` = 1 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }

    # set 0 for cleanLink in database
    public function offCleanLink($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_link` = 0 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }



    # get cleanUsername info from Database
    public function getCleanUsernameStat($group_id)
    {
        $stmt = $this->db->prepare("SELECT `clean_username` FROM `tb_settings` WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
        return $stmt->fetch();
    }

    # set 1 for cleanUsername in database
    public function onCleanUsername($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_username` = 1 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }

    # set 0 for cleanUsername in database
    public function offCleanUsername($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_username` = 0 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }



    # get cleanUsername info from Database
    public function getCleanBotStat($group_id)
    {
        $stmt = $this->db->prepare("SELECT `clean_bot` FROM `tb_settings` WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
        return $stmt->fetch();
    }

    # set 1 for cleanBot in database
    public function onCleanBot($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_bot` = 1 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }

    # set 0 for cleanBot in database
    public function offCleanBot($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_bot` = 0 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }



    # get cleanVedio info from Database
    public function getCleanVedioStat($group_id)
    {
        $stmt = $this->db->prepare("SELECT `clean_vedio` FROM `tb_settings` WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
        return $stmt->fetch();
    }

    # set 1 for cleanVedio in database
    public function onCleanVedio($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_vedio` = 1 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }

    # set 0 for cleanVedio in database
    public function offCleanVedio($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_vedio` = 0 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }



    # get cleanPhoto info from Database 
    public function getCleanPhotoStat($group_id)
    {
        $stmt = $this->db->prepare("SELECT `clean_photo` FROM `tb_settings` WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
        return $stmt->fetch();
    }

    # set 1 for cleanPhoto in database
    public function onCleanPhoto($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_photo` = 1 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }

    # set 0 for cleanPhoto in database
    public function offCleanPhoto($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `clean_photo` = 0 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }
}
