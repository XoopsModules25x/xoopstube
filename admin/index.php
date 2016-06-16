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
 * @copyright       2001-2016 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link            http://xoops.org/
 * @since           1.0.6
 */

include_once __DIR__ . '/admin_header.php';
xoops_cp_header();

$indexAdmin = new ModuleAdmin();

$start     = XoopsRequest::getInt('start', 0, 'POST');// xtubeCleanRequestVars($_REQUEST, 'start', 0);
$start1    = XoopsRequest::getInt('start1', 0, 'POST');// xtubeCleanRequestVars($_REQUEST, 'start1', 0);
$start2    = XoopsRequest::getInt('start2', 0, 'POST');// xtubeCleanRequestVars($_REQUEST, 'start2', 0);
$start3    = XoopsRequest::getInt('start3', 0, 'POST');// xtubeCleanRequestVars($_REQUEST, 'start3', 0);
$start4    = XoopsRequest::getInt('start4', 0, 'POST');// xtubeCleanRequestVars($_REQUEST, 'start4', 0);
$start5    = XoopsRequest::getInt('start5', 0, 'POST');// xtubeCleanRequestVars($_REQUEST, 'start5', 0);
$totalcats = XoopstubeUtilities::xtubeGetTotalCategoryCount();

$result = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken'));
list($totalbrokenvideos) = $GLOBALS['xoopsDB']->fetchRow($result);
$result2 = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_mod'));
list($totalmodrequests) = $GLOBALS['xoopsDB']->fetchRow($result2);
$result3 = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published = 0');
list($totalnewvideos) = $GLOBALS['xoopsDB']->fetchRow($result3);
$result4 = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published > 0');
list($totalvideos) = $GLOBALS['xoopsDB']->fetchRow($result4);

//$xxx='<a href="brokenvideo.php">' . _AM_XOOPSTUBE_SBROKENSUBMIT . '</a><b>';

$indexAdmin->addInfoBox(_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY);
if ($totalcats > 0) {
    $indexAdmin->addInfoBoxLine(_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY, '<infolabel>' . '<a href="category.php">' . _AM_XOOPSTUBE_SCATEGORY . '</a><b>' . '</infolabel>', $totalcats, 'Green');
} else {
    $indexAdmin->addInfoBoxLine(_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY, '<infolabel>' . _AM_XOOPSTUBE_SCATEGORY . '</infolabel>', $totalcats, 'Green');
}

if ($totalvideos > 0) {
    $indexAdmin->addInfoBoxLine(_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY, '<infolabel>' . '<a href="main.php">' . _AM_XOOPSTUBE_SFILES . '</a><b>' . '</infolabel>', $totalvideos, 'Green');
} else {
    $indexAdmin->addInfoBoxLine(_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY, '<infolabel>' . _AM_XOOPSTUBE_SFILES . '</infolabel>', $totalvideos, 'Green');
}

if ($totalnewvideos > 0) {
    $indexAdmin->addInfoBoxLine(_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY, '<infolabel>' . '<a href="newvideos.php">' . _AM_XOOPSTUBE_SNEWFILESVAL . '</a><b>' . '</infolabel>', $totalnewvideos, 'Red');
} else {
    $indexAdmin->addInfoBoxLine(_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY, '<infolabel>' . _AM_XOOPSTUBE_SNEWFILESVAL . '</infolabel>', $totalnewvideos, 'Red');
}
if ($totalmodrequests > 0) {
    $indexAdmin->addInfoBoxLine(_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY, '<infolabel>' . '<a href="modifications.php">' . _AM_XOOPSTUBE_SMODREQUEST . '</a><b>' . '</infolabel>', $totalmodrequests, 'Red');
} else {
    $indexAdmin->addInfoBoxLine(_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY, '<infolabel>' . _AM_XOOPSTUBE_SMODREQUEST . '</infolabel>', $totalmodrequests, 'Red');
}

if ($totalbrokenvideos > 0) {
    $indexAdmin->addInfoBoxLine(_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY, '<infolabel>' . '<a href="brokenvideo.php">' . _AM_XOOPSTUBE_SBROKENSUBMIT . '</a><b>' . '</infolabel><infotext>',
                                $totalbrokenvideos . '</infotext>', 'Red');
} else {
    $indexAdmin->addInfoBoxLine(_AM_XOOPSTUBE_MINDEX_VIDEOSUMMARY, '<infolabel>' . _AM_XOOPSTUBE_SBROKENSUBMIT . '</infolabel><infotext>', $totalbrokenvideos . '</infotext>', 'Red');
}

//------ create directories ---------------

$folderMode = $GLOBALS['xoopsModuleConfig']['dirmode'];
//include_once dirname(__DIR__) . '/class/utilities.php';
foreach (array_keys($uploadFolders) as $i) {
    XoopstubeUtilities::prepareFolder($uploadFolders[$i], $folderMode);
    $indexAdmin->addConfigBoxLine($uploadFolders[$i], 'folder');
    //    $indexAdmin->addConfigBoxLine(array($uploadFolders[$i], $folderMode), 'chmod');
}

echo $indexAdmin->addNavigation(basename(__FILE__));
echo $indexAdmin->renderIndex();

/*
//------ check directories ---------------
include_once dirname(__DIR__) . '/include/directorychecker.php';

$indexAdmin->addConfigBoxLine('');
$redirectFile = $_SERVER['PHP_SELF'];

$languageConstants = array(
    _AM_XOOPSTUBE_AVAILABLE,
    _AM_XOOPSTUBE_NOTAVAILABLE,
    _AM_XOOPSTUBE_CREATETHEDIR,
    _AM_XOOPSTUBE_NOTWRITABLE,
    _AM_XOOPSTUBE_SETMPERM,
    _AM_XOOPSTUBE_DIRCREATED,
    _AM_XOOPSTUBE_DIRNOTCREATED,
    _AM_XOOPSTUBE_PERMSET,
    _AM_XOOPSTUBE_PERMNOTSET
);

$path = $GLOBALS['xoopsModuleConfig']['uploaddir'] . '/';
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path, 0777, $languageConstants, $redirectFile));

$path = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['screenshots'] . '/';
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path, 0777, $languageConstants, $redirectFile));

$path = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['catimage'] . '/';
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path, 0777, $languageConstants, $redirectFile));

$path = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['mainimagedir'] . '/';
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path, 0777, $languageConstants, $redirectFile));

//echo $indexAdmin->addNavigation(basename(__FILE__));
//echo $indexAdmin->renderIndex();
//echo wfd_serverstats();
//---------------------------

xtubeFileChecks();

*/

include_once __DIR__ . '/admin_footer.php';
