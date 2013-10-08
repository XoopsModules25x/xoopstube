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

include_once dirname(__FILE__) . '/admin_header.php';
//include_once 'admin_header.php';
xoops_cp_header();

$indexAdmin = new ModuleAdmin();

global $xoopsDB;

$start     = xtubeCleanRequestVars($_REQUEST, 'start', 0);
$start1    = xtubeCleanRequestVars($_REQUEST, 'start1', 0);
$start2    = xtubeCleanRequestVars($_REQUEST, 'start2', 0);
$start3    = xtubeCleanRequestVars($_REQUEST, 'start3', 0);
$start4    = xtubeCleanRequestVars($_REQUEST, 'start4', 0);
$start5    = xtubeCleanRequestVars($_REQUEST, 'start5', 0);
$totalcats = xtubeGetTotalCategoryCount();

$result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xoopstube_broken'));
list($totalbrokenvideos) = $xoopsDB->fetchRow($result);
$result2 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xoopstube_mod'));
list($totalmodrequests) = $xoopsDB->fetchRow($result2);
$result3 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE published = 0');
list($totalnewvideos) = $xoopsDB->fetchRow($result3);
$result4 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE published > 0');
list($totalvideos) = $xoopsDB->fetchRow($result4);

//$xxx='<a href="brokenvideo.php">' . _AM_XTUBE_SBROKENSUBMIT . '</a><b>';

$indexAdmin->addInfoBox(_AM_XTUBE_MINDEX_VIDEOSUMMARY);
if ($totalcats > 0) {
    $indexAdmin->addInfoBoxLine(
        _AM_XTUBE_MINDEX_VIDEOSUMMARY,
        "<infolabel>" . '<a href="category.php">' . _AM_XTUBE_SCATEGORY . '</a><b>' . "</infolabel>",
        $totalcats,
        'Green'
    );
} else {
    $indexAdmin->addInfoBoxLine(
        _AM_XTUBE_MINDEX_VIDEOSUMMARY,
        "<infolabel>" . _AM_XTUBE_SCATEGORY . "</infolabel>",
        $totalcats,
        'Green'
    );
}

if ($totalvideos > 0) {
    $indexAdmin->addInfoBoxLine(
        _AM_XTUBE_MINDEX_VIDEOSUMMARY,
        "<infolabel>" . '<a href="main.php">' . _AM_XTUBE_SFILES . '</a><b>' . "</infolabel>",
        $totalvideos,
        'Green'
    );
} else {
    $indexAdmin->addInfoBoxLine(
        _AM_XTUBE_MINDEX_VIDEOSUMMARY,
        "<infolabel>" . _AM_XTUBE_SFILES . "</infolabel>",
        $totalvideos,
        'Green'
    );
}

if ($totalnewvideos > 0) {
    $indexAdmin->addInfoBoxLine(
        _AM_XTUBE_MINDEX_VIDEOSUMMARY,
        "<infolabel>" . '<a href="newvideos.php">' . _AM_XTUBE_SNEWFILESVAL . '</a><b>' . "</infolabel>",
        $totalnewvideos,
        'Red'
    );
} else {
    $indexAdmin->addInfoBoxLine(
        _AM_XTUBE_MINDEX_VIDEOSUMMARY,
        "<infolabel>" . _AM_XTUBE_SNEWFILESVAL . "</infolabel>",
        $totalnewvideos,
        'Red'
    );
}
if ($totalmodrequests > 0) {
    $indexAdmin->addInfoBoxLine(
        _AM_XTUBE_MINDEX_VIDEOSUMMARY,
        "<infolabel>" . '<a href="modifications.php">' . _AM_XTUBE_SMODREQUEST . '</a><b>' . "</infolabel>",
        $totalmodrequests,
        'Red'
    );
} else {
    $indexAdmin->addInfoBoxLine(
        _AM_XTUBE_MINDEX_VIDEOSUMMARY,
        "<infolabel>" . _AM_XTUBE_SMODREQUEST . "</infolabel>",
        $totalmodrequests,
        'Red'
    );
}

if ($totalbrokenvideos > 0) {
    $indexAdmin->addInfoBoxLine(
        _AM_XTUBE_MINDEX_VIDEOSUMMARY,
        "<infolabel>" . '<a href="brokenvideo.php">' . _AM_XTUBE_SBROKENSUBMIT . '</a><b>' . "</infolabel><infotext>",
        $totalbrokenvideos . "</infotext>",
        'Red'
    );
} else {
    $indexAdmin->addInfoBoxLine(
        _AM_XTUBE_MINDEX_VIDEOSUMMARY,
        "<infolabel>" . _AM_XTUBE_SBROKENSUBMIT . "</infolabel><infotext>",
        $totalbrokenvideos . "</infotext>",
        'Red'
    );
}

echo $indexAdmin->addNavigation('index.php');
echo $indexAdmin->renderIndex();

xtube_filechecks();

include 'admin_footer.php';
//xoops_cp_footer();
