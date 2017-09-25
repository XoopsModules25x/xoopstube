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
 * @copyright       2001-2016 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org/
 * @since           1.0.6
 *
 * @param int    $cid
 * @param string $permType
 * @param bool   $redirect
 *
 * @return bool
 */

function xtubeCheckSearchGroups($cid = 0, $permType = 'XTubeCatPerm', $redirect = false)
{
    $moduleDirName = basename(dirname(__DIR__));
    //    $modulePath = dirname(__DIR__);

    $groups       = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gpermHandler = xoops_getHandler('groupperm');

    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);

    if (!$gpermHandler->checkRight($permType, $cid, $groups, $module->getVar('mid'))) {
        if (false === $redirect) {
            return false;
        } else {
            redirect_header('index.php', 3, _NOPERM);
        }
    }
    unset($module);

    return true;
}

/**
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 *
 * @return array
 */
function xtubeSearch($queryarray, $andor, $limit, $offset, $userid)
{
    $sql    = 'SELECT cid, pid FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat');
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($_search_group_check = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $_search_check_array[$_search_group_check['cid']] = $_search_group_check;
    }

    $sql = 'SELECT lid, cid, title, submitter, published, description FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos');
    $sql .= ' WHERE published > 0 AND published <= ' . time() . ' AND ( expired = 0 OR expired > ' . time() . ') AND offline = 0 AND cid > 0';

    if (0 !== $userid) {
        $sql .= ' AND submitter=' . $userid . ' ';
    }

    // because count() returns 1 even if a supplied variable
    // is not an array, we must check if $querryarray is really an array
    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((title LIKE LOWER('%$queryarray[0]%') OR LOWER(description) LIKE LOWER('%$queryarray[0]%'))";
        for ($i = 1; $i < $count; ++$i) {
            $sql .= " $andor ";
            $sql .= "(title LIKE LOWER('%$queryarray[$i]%') OR LOWER(description) LIKE LOWER('%$queryarray[$i]%'))";
        }
        $sql .= ') ';
    }
    $sql    .= 'ORDER BY published DESC';
    $result = $GLOBALS['xoopsDB']->query($sql, $limit, $offset);
    $ret    = [];
    $i      = 0;

    while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
        // Following is commented out because it can cause a conflict with View Account function (userinfo.php)
        //        if ( false === xtubeCheckSearchGroups( $myrow['cid'] ) ) {
        //            continue;
        //        }
        $ret[$i]['image'] = 'assets/images/size2.gif';
        $ret[$i]['link']  = 'singlevideo.php?cid=' . $myrow['cid'] . '&amp;lid=' . $myrow['lid'];
        $ret[$i]['title'] = $myrow['title'];
        $ret[$i]['time']  = $myrow['published'];
        $ret[$i]['uid']   = $myrow['submitter'];
        ++$i;
    }
    unset($_search_check_array);

    return $ret;
}
