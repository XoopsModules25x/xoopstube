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
 * @param $options
 *
 * @return array|null
 */

use XoopsModules\Xoopstube;

/**
 * @param $options
 * @return array|null
 */
function xtubeShowTagBlockCloud($options)
{
    $moduleDirName = basename(dirname(__DIR__));
//    require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/Utility.php';
    if (Xoopstube\Utility::isModuleTagInstalled()) {
        require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_cloud_show($options, $moduleDirName);
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
    $moduleDirName = basename(dirname(__DIR__));
//    require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/Utility.php';
    if (Xoopstube\Utility::isModuleTagInstalled()) {
        require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

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
    $moduleDirName = basename(dirname(__DIR__));
//    require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/Utility.php';
    if (Xoopstube\Utility::isModuleTagInstalled()) {
        require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_top_show($options, $moduleDirName);
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
    $moduleDirName = basename(dirname(__DIR__));
//    require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/class/Utility.php';
    if (Xoopstube\Utility::isModuleTagInstalled()) {
        require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_top_edit($options);
    }

    return null;
}
