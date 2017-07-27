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
 */

use Xmf\Request;

include __DIR__ . '/../../mainfile.php';
$com_itemid = Request::getInt('com_itemid', 0, 'GET');
if ($com_itemid > 0) {
    // Get file title
    $sql            = 'SELECT title FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $com_itemid;
    $result         = $GLOBALS['xoopsDB']->query($sql);
    $row            = $GLOBALS['xoopsDB']->fetchArray($result);
    $com_replytitle = $row['title'];
    include XOOPS_ROOT_PATH . '/include/comment_new.php';
}
