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
 * @param $videoload_id
 * @param $total_num
 */

// comment callback functions

function xtubeUpdateComment($videoload_id, $total_num)
{
    $db  = \XoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'UPDATE ' . $db->prefix('xoopstube_videos') . ' SET comments=' . $total_num . ' WHERE lid=' . $videoload_id;
    $db->query($sql);
}

/**
 * @param $comment
 */
function xtubeApproveComment(&$comment)
{
    // notification mail here
}
