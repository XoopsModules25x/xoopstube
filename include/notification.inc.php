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
 * @param $category
 * @param $item_id
 *
 * @return null
 */

function xtubeNotifyIteminfo($category, $item_id)
{
    global $xoopsModule;

    $moduleDirName = basename(dirname(__DIR__));
    //    $modulePath = dirname(__DIR__);

    if (empty($xoopsModule) || 'xoopstube' !== $xoopsModule->getVar('dirname')) {
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname($moduleDirName);
        $configHandler = xoops_getHandler('config');
        $config        = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
    } else {
        $module = $xoopsModule;
        $config = $GLOBALS['xoopsModuleConfig'];
    }

    if ('global' === $category) {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    if ('category' === $category) {
        // Assume we have a valid category id
        $sql = 'SELECT title FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' WHERE cid=' . $item_id;
        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            return false;
        }
        $result_array = $GLOBALS['xoopsDB']->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/xoopstube/viewcat.php?cid=' . $item_id;

        return $item;
    }

    if ('video' === $category) {
        // Assume we have a valid file id
        $sql = 'SELECT cid,title FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $item_id;
        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            return false;
        }
        $result_array = $GLOBALS['xoopsDB']->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/xoopstube/singlevideo.php?cid=' . $result_array['cid'] . '&amp;lid=' . $item_id;

        return $item;
    }

    return null;
}
