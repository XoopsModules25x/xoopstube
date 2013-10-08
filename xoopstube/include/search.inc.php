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
 * @copyright       2001-2013 The XOOPS Project
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version         $Id$
 * @link            http://sourceforge.net/projects/xoops/
 * @since           1.0.6
 */


function xtubeCheckSearchGroups($cid = 0, $permType = 'XTubeCatPerm', $redirect = false)
{
    global $xoopsUser;

    $mydirname = basename(dirname(dirname(__FILE__)));
//    $mydirpath = dirname(dirname(__FILE__));

    $groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler = & xoops_gethandler('groupperm');

    $module_handler = & xoops_gethandler('module');
    $module         = & $module_handler->getByDirname($mydirname);

    if (!$gperm_handler->checkRight($permType, $cid, $groups, $module->getVar('mid'))) {
        if ($redirect == false) {
            return false;
        } else {
            redirect_header('index.php', 3, _NOPERM);
            exit();
        }
    }
    unset($module);
    return true;
}

function xtubeSearch($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB, $xoopsUser;

    $sql    = "SELECT cid, pid FROM " . $xoopsDB->prefix('xoopstube_cat');
    $result = $xoopsDB->query($sql);
    while ($_search_group_check = $xoopsDB->fetchArray($result)) {
        $_search_check_array[$_search_group_check['cid']] = $_search_group_check;
    }

    $sql = "SELECT lid, cid, title, submitter, published, description FROM " . $xoopsDB->prefix('xoopstube_videos');
    $sql .= " WHERE published > 0 AND published <= " . time() . " AND ( expired = 0 OR expired > " . time(
        ) . ") AND offline = 0 AND cid > 0";

    if ($userid != 0) {
        $sql .= " AND submitter=" . $userid . " ";
    }

    // because count() returns 1 even if a supplied variable
    // is not an array, we must check if $querryarray is really an array
    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((title LIKE LOWER('%$queryarray[0]%') OR LOWER(description) LIKE LOWER('%$queryarray[0]%'))";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "(title LIKE LOWER('%$queryarray[$i]%') OR LOWER(description) LIKE LOWER('%$queryarray[$i]%'))";
        }
        $sql .= ") ";
    }
    $sql .= "ORDER BY published DESC";
    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret    = array();
    $i      = 0;

    while ($myrow = $xoopsDB->fetchArray($result)) {
// Following is commented out because it can cause a conflict with View Account function (userinfo.php)      
//        if ( false == xtubeCheckSearchGroups( $myrow['cid'] ) ) {
//            continue;
//        }
        $ret[$i]['image'] = 'images/size2.gif';
        $ret[$i]['link']  = 'singlevideo.php?cid=' . $myrow['cid'] . '&amp;lid=' . $myrow['lid'];
        $ret[$i]['title'] = $myrow['title'];
        $ret[$i]['time']  = $myrow['published'];
        $ret[$i]['uid']   = $myrow['submitter'];
        $i++;
    }
    unset($_search_check_array);
    return $ret;
}
