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
 * @param $options
 *
 * @return array|null
 */

function xtubeShowTagBlockCloud($options)
{
    $mydirname = basename(dirname(__DIR__));
    include_once XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/include/functions.php';
    if (xtubeIsModuleTagInstalled()) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_cloud_show($options, $mydirname);
    }

    return null;
}

/**
 * @param $options
 *
 * @return null|string
 */
function xtubeEditTagBlockCloud($options)
{
    $mydirname = basename(dirname(__DIR__));
    include_once XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/include/functions.php';
    if (xtubeIsModuleTagInstalled()) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_cloud_edit($options);
    }

    return null;
}

/**
 * @param $options
 *
 * @return array|null
 */
function xtubeShowTagBlockTop($options)
{
    $mydirname = basename(dirname(__DIR__));
    include_once XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/include/functions.php';
    if (xtubeIsModuleTagInstalled()) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_top_show($options, $mydirname);
    }

    return null;
}

/**
 * @param $options
 *
 * @return null|string
 */
function xtubeEditTagBlockTop($options)
{
    $mydirname = basename(dirname(__DIR__));
    include_once XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/include/functions.php';
    if (xtubeIsModuleTagInstalled()) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_top_edit($options);
    }

    return null;
}
