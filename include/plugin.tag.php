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
 * @author          Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @copyright       2001-2013 The XOOPS Project
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link            http://sourceforge.net/projects/xoops/
 * @since           1.0.6
 */

// defined('XOOPS_ROOT_PATH') || die('XOOPS Root Path not defined');

/**
 * Get item fields:
 * title
 * content
 * time
 * link
 * uid
 * uname
 * tags
 *
 * @var array $items associative array of items: [modid][catid][itemid]
 *
 * @return boolean
 *
 */

function xoopstube_tag_iteminfo(&$items)
{
    $moduleDirName = basename(dirname(__DIR__));

    if (empty($items) || !is_array($items)) {
        return false;
    }

    global $xoopsDB;
    $myts = \MyTextSanitizer::getInstance();

    $items_id = [];

    foreach (array_keys($items) as $catId) {
        // Some handling here to build the link upon catid
        // If catid is not used, just skip it
        foreach (array_keys($items[$catId]) as $item_id) {
            // In article, the item_id is "art_id"
            $items_id[] = (int)$item_id;
        }
    }

    foreach (array_keys($items) as $catId) {
        foreach (array_keys($items[$catId]) as $item_id) {
            $sql                     = 'SELECT l.lid, l.cid AS lcid, l.title AS ltitle, l.published, l.cid, l.submitter, l.description, l.item_tag, c.title AS ctitle FROM '
                                       . $xoopsDB->prefix('xoopstube_videos')
                                       . ' l, '
                                       . $xoopsDB->prefix('xoopstube_cat')
                                       . ' c WHERE l.lid='
                                       . $item_id
                                       . ' AND l.cid=c.cid AND l.status>0 ORDER BY l.published DESC';
            $result                  = $xoopsDB->query($sql);
            $row                     = $xoopsDB->fetchArray($result);
            $lcid                    = $row['lcid'];
            $items[$catId][$item_id] = [
                'title'   => $row['ltitle'],
                'uid'     => $row['submitter'],
                'link'    => "singlevideo.php?cid=$lcid&amp;lid=$item_id",
                'time'    => $row['published'],
                'tags'    => $row['item_tag'],
                'content' => $row['description']
            ];
        }
    }

    return null;
}

/** Remove orphan tag-item links *
 *
 * @param $mid
 */
function xoopstube_tag_synchronization($mid)
{
    // Optional
}
