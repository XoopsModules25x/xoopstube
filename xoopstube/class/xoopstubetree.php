<?php

/**
 * Module: XoopsTube
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * PHP version 5
 *
 * @category        Module
 * @package         Xoopstube
 * @author          XOOPS Development Team
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright       2001-2013 The XOOPS Project
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version         $Id: xoopstubetree.php 11722 2013-06-19 16:47:58Z beckmi $
 * @link            http://sourceforge.net/projects/xoops/
 * @since           1.0.6
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Abstract base class for forms
 *
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    John Neill <catzwolf@xoops.org>
 * @copyright copyright (c) XOOPS.org
 * @package   xoopstubetree
 * @access    public
 */
class XoopsTubeTree
{
    public $table; //table with parent-child structure
    public $id; //name of unique id for records in table $table
    public $pid; // name of parent id used in table $table
    public $order; //specifies the order of query results
    public $title; // name of a field in table $table which will be used when  selection box and paths are generated
    public $db;

    //constructor of class XoopsTree
    //sets the names of table, unique id, and parend id
    public function __construct($table_name, $id_name, $pid_name)
    {
        $this->db = & XoopsDatabaseFactory::getDatabaseConnection();;
        $this->table = $table_name;
        $this->id    = $id_name;
        $this->pid   = $pid_name;
    }

    // returns an array of first child objects for a given id($sel_id)
    public function getFirstChild($sel_id, $order = "")
    {
        $sel_id = intval($sel_id);
        $arr    = array();
        $sql    = "SELECT * FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . "";
        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $arr;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            array_push($arr, $myrow);
        }

        return $arr;
    }

    // returns an array of all FIRST child ids of a given id($sel_id)
    public function getFirstChildId($sel_id)
    {
        $sel_id  = intval($sel_id);
        $idarray = array();
        $result  = $this->db->query(
            "SELECT " . $this->id . " FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . ""
        );
        $count   = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $idarray;
        }
        while (list ($id) = $this->db->fetchRow($result)) {
            array_push($idarray, $id);
        }

        return $idarray;
    }

    //returns an array of ALL child ids for a given id($sel_id)
    public function getAllChildId($sel_id, $order = "", $idarray = array())
    {
        $sel_id = intval($sel_id);
        $sql    = "SELECT " . $this->id . " FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . "";
        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $idarray;
        }
        while (list ($r_id) = $this->db->fetchRow($result)) {
            array_push($idarray, $r_id);
            $idarray = $this->getAllChildId($r_id, $order, $idarray);
        }

        return $idarray;
    }

    //returns an array of ALL parent ids for a given id($sel_id)
    public function getAllParentId($sel_id, $order = "", $idarray = array())
    {
        $sel_id = intval($sel_id);
        $sql    = "SELECT " . $this->pid . " FROM " . $this->table . " WHERE " . $this->id . "=" . $sel_id . "";
        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        list ($r_id) = $this->db->fetchRow($result);
        if ($r_id == 0) {
            return $idarray;
        }
        array_push($idarray, $r_id);
        $idarray = $this->getAllParentId($r_id, $order, $idarray);

        return $idarray;
    }

    //generates path from the root id to a given id($sel_id)
    // the path is delimetered with "/"
    public function getPathFromId($sel_id, $title, $path = "")
    {
        $sel_id = intval($sel_id);
        $result = $this->db->query(
            "SELECT " . $this->pid . ", " . $title . " FROM " . $this->table . " WHERE " . $this->id . "=$sel_id"
        );
        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list ($parentid, $name) = $this->db->fetchRow($result);
        $myts = MyTextSanitizer::getInstance();
        $name = $myts->htmlspecialchars($name);
        $path = "/" . $name . $path . "";
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getPathFromId($parentid, $title, $path);

        return $path;
    }

    //makes a nicely ordered selection box
    //$preset_id is used to specify a preselected item
    //set $none to 1 to add a option with value 0
    public function makeMySelBox($title, $order = "", $preset_id = 0, $none = 0, $sel_name = "", $onchange = "")
    {
        if ($sel_name == "") {
            $sel_name = $this->id;
        }
        $myts = MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ($onchange != "") {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = "SELECT " . $this->id . ", " . $title . " FROM " . $this->table . " WHERE " . $this->pid . "=0";
        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            echo "<option value='0'>----</option>\n";
        }
        while (list ($catid, $name) = $this->db->fetchRow($result)) {
            $sel = "";
            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }
            echo "<option value='$catid'$sel>$name</option>\n";
            $sel = "";
            $arr = $this->getChildTreeArray($catid, $order);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace(".", "--", $option['prefix']);
                $catpath          = $option['prefix'] . "&nbsp;" . $myts->htmlspecialchars($option[$title]);
                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }
                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
                $sel = "";
            }
        }
        echo "</select>\n";
    }

    //generates nicely formatted linked path from the root id to a given id
    public function getNicePathFromId($sel_id, $title, $funcURL, $path = "")
    {
        $path   = !empty($path) ? "&nbsp;:&nbsp;" . $path : $path;
        $sel_id = intval($sel_id);
        $sql    = "SELECT " . $this->pid . ", " . $title . " FROM " . $this->table . " WHERE " . $this->id . "=$sel_id";
        $result = $this->db->query($sql);
        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list ($parentid, $name) = $this->db->fetchRow($result);
        $myts = MyTextSanitizer::getInstance();
        $name = $myts->htmlspecialchars($name);
        $path = "<a href='" . $funcURL . "&amp;" . $this->id . "=" . $sel_id . "'>" . $name . "</a>" . $path . "";
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getNicePathFromId($parentid, $title, $funcURL, $path);

        return $path;
    }

    //generates id path from the root id to a given id
    // the path is delimetered with "/"
    public function getIdPathFromId($sel_id, $path = "")
    {
        $sel_id = intval($sel_id);
        $result = $this->db->query(
            "SELECT " . $this->pid . " FROM " . $this->table . " WHERE " . $this->id . "=$sel_id"
        );
        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list ($parentid) = $this->db->fetchRow($result);
        $path = "/" . $sel_id . $path . "";
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getIdPathFromId($parentid, $path);

        return $path;
    }

    /**
     * Enter description here...
     *
     * @param int    $sel_id
     * @param string $order
     * @param array  $parray
     * @return array
     */
    public function getAllChild($sel_id = 0, $order = "", $parray = array())
    {
        $sel_id = intval($sel_id);
        $sql    = "SELECT * FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . "";
        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $parray;
        }
        while ($row = $this->db->fetchArray($result)) {
            array_push($parray, $row);
            $parray = $this->getAllChild($row[$this->id], $order, $parray);
        }

        return $parray;
    }

    /**
     * Enter description here...
     *
     * @param int    $sel_id
     * @param string $order
     * @param array  $parray
     * @param string $r_prefix
     * @return array
     */
    public function getChildTreeArray($sel_id = 0, $order = "", $parray = array(), $r_prefix = "")
    {
        $sel_id = intval($sel_id);
        $sql    = "SELECT * FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . "";
        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $parray;
        }
        while ($row = $this->db->fetchArray($result)) {
            $row['prefix'] = $r_prefix . ".";
            array_push($parray, $row);
            $parray = $this->getChildTreeArray($row[$this->id], $order, $parray, $row['prefix']);
        }

        return $parray;
    }
}
