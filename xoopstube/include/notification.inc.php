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
 *
 * @param $category
 * @param $item_id
 *
 * @return null
 */

function xtubeNotifyIteminfo($category, $item_id)
{
    global $xoopsModule, $xoopsModuleConfig;

    $mydirname = basename(dirname(__DIR__));
//    $mydirpath = dirname(__DIR__);

    if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != 'xoopstube') {
        $module_handler =& xoops_gethandler('module');
        $module         =& $module_handler->getByDirname($mydirname);
        $config_handler =& xoops_gethandler('config');
        $config         =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
    } else {
        $module =& $xoopsModule;
        $config =& $xoopsModuleConfig;
    }

    if ($category == 'global') {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    global $xoopsDB;
    if ($category == 'category') {
        // Assume we have a valid category id
        $sql = "SELECT title FROM " . $xoopsDB->prefix('xoopstube_cat') . " WHERE cid=" . $item_id;
        if (!$result = $xoopsDB->query($sql)) {
            return false;
        }
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/xoopstube/viewcat.php?cid=' . $item_id;

        return $item;
    }

    if ($category == 'video') {
        // Assume we have a valid file id
        $sql = "SELECT cid,title FROM " . $xoopsDB->prefix('xoopstube_videos') . " WHERE lid=" . $item_id;
        if (!$result = $xoopsDB->query($sql)) {
            return false;
        }
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']
                      = XOOPS_URL . '/modules/xoopstube/singlevideo.php?cid=' . $result_array['cid'] . '&amp;lid=' . $item_id;

        return $item;
    }

    return null;
}
