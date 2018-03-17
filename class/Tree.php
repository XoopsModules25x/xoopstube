<?php namespace XoopsModules\Xoopstube;

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
 * @copyright       2001-2016 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org/
 * @since           1.0.6
 */

use XoopsModules\Xoopstube;
use XoopsModules\Xoopstube\Common;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Abstract base class for forms
 *
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    John Neill <catzwolf@xoops.org>
 * @copyright copyright (c) XOOPS.org
 * @package   xoopstubetree
 * @access    public
 */
class Tree
{
    public $table; //table with parent-child structure
    public $id; //name of unique id for records in table $table
    public $pid; // name of parent id used in table $table
    public $order; //specifies the order of query results
    public $title; // name of a field in table $table which will be used when  selection box and paths are generated
    public $db;

    //constructor of class XoopsTree
    //sets the names of table, unique id, and parend id
    /**
     * @param $tableName
     * @param $idName
     * @param $pidName
     */
    public function __construct($tableName, $idName, $pidName)
    {
        $this->db    = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->table = $tableName;
        $this->id    = $idName;
        $this->pid   = $pidName;
    }

    // returns an array of first child objects for a given id($selectId)

    /**
     * @param        $selectId
     * @param string $order
     *
     * @return array
     */
    public function getFirstChild($selectId, $order = '')
    {
        $selectId = (int)$selectId;
        $arr      = [];
        $sql      = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $selectId . '';
        if ('' !== $order) {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if (0 == $count) {
            return $arr;
        }
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            array_push($arr, $myrow);
        }

        return $arr;
    }

    // returns an array of all FIRST child ids of a given id($selectId)

    /**
     * @param $selectId
     *
     * @return array
     */
    public function getFirstChildId($selectId)
    {
        $selectId = (int)$selectId;
        $idarray  = [];
        $result   = $this->db->query('SELECT ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $selectId . '');
        $count    = $this->db->getRowsNum($result);
        if (0 == $count) {
            return $idarray;
        }
        while (false !== (list($id) = $this->db->fetchRow($result))) {
            array_push($idarray, $id);
        }

        return $idarray;
    }

    //returns an array of ALL child ids for a given id($selectId)

    /**
     * @param        $selectId
     * @param string $order
     * @param array  $idarray
     *
     * @return array
     */
    public function getAllChildId($selectId, $order = '', array $idarray = [])
    {
        $selectId = (int)$selectId;
        $sql      = 'SELECT ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $selectId . '';
        if ('' !== $order) {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if (0 == $count) {
            return $idarray;
        }
        while (false !== (list($r_id) = $this->db->fetchRow($result))) {
            array_push($idarray, $r_id);
            $idarray = $this->getAllChildId($r_id, $order, $idarray);
        }

        return $idarray;
    }

    //returns an array of ALL parent ids for a given id($selectId)

    /**
     * @param        $selectId
     * @param string $order
     * @param array  $idarray
     *
     * @return array
     */
    public function getAllParentId($selectId, $order = '', array $idarray = [])
    {
        $selectId = (int)$selectId;
        $sql      = 'SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . '=' . $selectId . '';
        if ('' !== $order) {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        list($r_id) = $this->db->fetchRow($result);
        if (0 == $r_id) {
            return $idarray;
        }
        array_push($idarray, $r_id);
        $idarray = $this->getAllParentId($r_id, $order, $idarray);

        return $idarray;
    }

    //generates path from the root id to a given id($selectId)
    // the path is delimetered with "/"
    /**
     * @param        $selectId
     * @param        $title
     * @param string $path
     *
     * @return string
     */
    public function getPathFromId($selectId, $title, $path = '')
    {
        $selectId = (int)$selectId;
        $result   = $this->db->query('SELECT ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$selectId");
        if (0 == $this->db->getRowsNum($result)) {
            return $path;
        }
        list($parentid, $name) = $this->db->fetchRow($result);
        $myts = \MyTextSanitizer::getInstance();
        $name = $myts->htmlspecialchars($name);
        $path = '/' . $name . $path . '';
        if (0 == $parentid) {
            return $path;
        }
        $path = $this->getPathFromId($parentid, $title, $path);

        return $path;
    }

    //makes a nicely ordered selection box
    //$preset_id is used to specify a preselected item
    //set $none to 1 to add a option with value 0
    /**
     * @param        $title
     * @param string $order
     * @param int    $preset_id
     * @param int    $none
     * @param string $sel_name
     * @param string $onchange
     */
    public function makeMySelBox($title, $order = '', $preset_id = 0, $none = 0, $sel_name = '', $onchange = '')
    {
        if ('' === $sel_name) {
            $sel_name = $this->id;
        }
        $myts = \MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ('' !== $onchange) {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = 'SELECT ' . $this->id . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=0';
        if ('' !== $order) {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            echo "<option value='0'>----</option>\n";
        }
        while (false !== (list($catid, $name) = $this->db->fetchRow($result))) {
            $sel = '';
            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }
            echo "<option value='$catid'$sel>$name</option>\n";
            $sel = '';
            $arr = $this->getChildTreeArray($catid, $order);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace('.', '--', $option['prefix']);
                $catpath          = $option['prefix'] . '&nbsp;' . $myts->htmlspecialchars($option[$title]);
                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }
                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
                $sel = '';
            }
        }
        echo "</select>\n";
    }

    //generates nicely formatted linked path from the root id to a given id

    /**
     * @param        $selectId
     * @param        $title
     * @param        $funcURL
     * @param string $path
     *
     * @return string
     */
    public function getNicePathFromId($selectId, $title, $funcURL, $path = '')
    {
        $path     = !empty($path) ? $path : $path;
        $selectId = (int)$selectId;
        $sql      = 'SELECT ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$selectId";
        $result   = $this->db->query($sql);
        if (0 == $this->db->getRowsNum($result)) {
            return $path;
        }
        list($parentid, $name) = $this->db->fetchRow($result);
        $myts = \MyTextSanitizer::getInstance();
        $name = $myts->htmlspecialchars($name);
        $path = "<li><a href='" . $funcURL . '&amp;' . $this->id . '=' . $selectId . "'>" . $name . '</a></li>' . $path . '';
        if (0 == $parentid) {
            return $path;
        }
        $path = $this->getNicePathFromId($parentid, $title, $funcURL, $path);

        return $path;
    }

    //generates id path from the root id to a given id
    // the path is delimetered with "/"
    /**
     * @param        $selectId
     * @param string $path
     *
     * @return string
     */
    public function getIdPathFromId($selectId, $path = '')
    {
        $selectId = (int)$selectId;
        $result   = $this->db->query('SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$selectId");
        if (0 == $this->db->getRowsNum($result)) {
            return $path;
        }
        list($parentid) = $this->db->fetchRow($result);
        $path = '/' . $selectId . $path . '';
        if (0 == $parentid) {
            return $path;
        }
        $path = $this->getIdPathFromId($parentid, $path);

        return $path;
    }

    /**
     * Enter description here...
     *
     * @param int    $selectId
     * @param string $order
     * @param array  $parray
     *
     * @return array
     */
    public function getAllChild($selectId = 0, $order = '', array $parray = [])
    {
        $selectId = (int)$selectId;
        $sql      = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $selectId . '';
        if ('' !== $order) {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if (0 == $count) {
            return $parray;
        }
        while (false !== ($row = $this->db->fetchArray($result))) {
            array_push($parray, $row);
            $parray = $this->getAllChild($row[$this->id], $order, $parray);
        }

        return $parray;
    }

    /**
     * Enter description here...
     *
     * @param int    $selectId
     * @param string $order
     * @param array  $parray
     * @param string $r_prefix
     *
     * @return array
     */
    public function getChildTreeArray($selectId = 0, $order = '', array $parray = [], $r_prefix = '')
    {
        $selectId = (int)$selectId;
        $sql      = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $selectId . '';
        if ('' !== $order) {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if (0 == $count) {
            return $parray;
        }
        while (false !== ($row = $this->db->fetchArray($result))) {
            $row['prefix'] = $r_prefix . '.';
            array_push($parray, $row);
            $parray = $this->getChildTreeArray($row[$this->id], $order, $parray, $row['prefix']);
        }

        return $parray;
    }
}
