<?php

class GroupConnection extends Connection
{
    # add new group to Database
    public function addNewGroup($group_id , $group_name)
    {
        $stmt = $this->db->prepare("INSERT INTO `tb_groups` (`group_id`, `group_name`) VALUES (?, ?)");
        $stmt->execute([$group_id, $group_name]);
    }

    # get group info from Database
    public function getGroup($group_id , $group_name)
    {
        $stmt = $this->db->prepare("SELECT * FROM `tb_userStat` WHERE `group_id` = ? AND `group_name` = ? ");
        $stmt->execute([$group_id , $group_name]);
        return $stmt->fetch();
    }

    # get permision of a group from Database
    public function getPermision($group_id)
    {
        $stmt = $this->db->prepare("SELECT `permision` FROM `tb_groups` WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
        return $stmt->fetch()->permision;
    }

    # set 1 for permision in database
    public function onPermision($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_groups` SET `permision` = 1 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }

    # set 0 for permision in database
    public function offPermision($group_id)
    {
        $stmt = $this->db->prepare("UPDATE `tb_settings` SET `permision` = 0 WHERE `group_id` = ?");
        $stmt->execute([$group_id]);
    }

}
