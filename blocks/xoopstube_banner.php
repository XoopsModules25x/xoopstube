<?php
/**
 * XoopsTube - a multicategory video management module
 *
 * Based upon WF-Links
 *
 * File: blocks/xoopstube_banner.php
 *
 * @copyright        https://xoops.org/ XOOPS Project
 * @copyright        XOOPS_copyrights.txt
 * @copyright        http://www.impresscms.org/ The ImpressCMS Project
 * @license          GNU General Public License (GPL)
 *                   a copy of the GNU license is enclosed.
 * ----------------------------------------------------------------------------------------------------------
 * @package          XoopsTube
 * @since            1.00
 * @author           McDonald
 *
 * @param $options
 *
 * @return array
 */

use XoopsModules\Xoopstube;

/**
 * @param $options
 * @return array
 */
function xtubeShowBannerB($options)
{
    $moduleDirName = basename(dirname(__DIR__));

    $block = [];
    $time  = time();
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler         = xoops_getHandler('module');
    $xoopstubeModule       = $moduleHandler->getByDirname($moduleDirName);
    $configHandler         = xoops_getHandler('config');
    $xoopstubeModuleConfig = $configHandler->getConfigsByCat(0, $xoopstubeModule->getVar('mid'));

    $result = $GLOBALS['xoopsDB']->query('SELECT a.cid AS acid, a.title, a.client_id, a.banner_id, b.bid, b.cid, b.imptotal, b.impmade, b.clicks FROM '
                                         . $GLOBALS['xoopsDB']->prefix('xoopstube_cat')
                                         . ' a, '
                                         . $GLOBALS['xoopsDB']->prefix('banner')
                                         . ' b WHERE (b.cid = a.client_id) OR (b.bid = a.banner_id) ORDER BY b.cid, b.bid, a.title ASC');

    while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $impmade    = $myrow['impmade'];
        $clicks     = $myrow['clicks'];
        $imptotal   = $myrow['imptotal'];
        $bannerload = [];
        $result2    = $GLOBALS['xoopsDB']->query('SELECT name FROM ' . $GLOBALS['xoopsDB']->prefix('bannerclient') . ' WHERE cid=' . (int)$myrow['cid']);
        $myclient   = $GLOBALS['xoopsDB']->fetchArray($result2);
        if (0 == $impmade) {
            $percent = 0;
        } else {
            $percent = substr(100 * $clicks / $impmade, 0, 5);
        }
        if (0 == $imptotal) {
            $left = 'Unlimited';
        } else {
            $left = (int)$imptotal - (int)$impmade;
        }
        $bannerload['cat']      = (int)$myrow['acid'];
        $bannerload['bid']      = (int)$myrow['bid'];
        $bannerload['cid']      = (int)$myrow['cid'];
        $bannerload['imptotal'] = (int)$myrow['imptotal'];
        $bannerload['impmade']  = (int)$myrow['impmade'];
        $bannerload['impleft']  = $left;
        $bannerload['clicks']   = (int)$myrow['clicks'];
        $bannerload['client']   = $myclient['name'];
        $bannerload['percent']  = $percent;
        $bannerload['cattitle'] = $myrow['title'];
        $bannerload['dirname']  = $xoopstubeModule->getVar('dirname');
        $block['banners'][]     = $bannerload;
    }
    unset($_block_check_array);

    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function xtubeEditBannerB($options)
{
    $form = '';

    return $form;
}
