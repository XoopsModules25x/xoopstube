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
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @since           1.0.6
 * @link            https://xoops.org/
 */

use Xmf\Request;
use XoopsModules\Xoopstube\{
    Utility,
    Tree
};

$GLOBALS['xoopsOption']['template_main'] = 'xoopstube_newlistindex.tpl';
require_once __DIR__ . '/header.php';


//require_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet('modules/' . $moduleDirName . '/assets/css/xtubestyle.css');

global $xoopsModule;

$catarray['imageheader'] = Utility::renderImageHeader();
$xoopsTpl->assign('catarray', $catarray);
if (!isset($_GET['newvideoshowdays'])) {
    redirect_header('newlist.php?newvideoshowdays=7', 1, '');
}

if (Request::hasVar('newvideoshowdays', 'GET')) {
    $newvideoshowdays = Request::getInt('newvideoshowdays', 7, 'GET');
    if (7 !== $newvideoshowdays) {
        if (14 !== $newvideoshowdays) {
            if (30 !== $newvideoshowdays) {
                redirect_header('newlist.php?newvideoshowdays=7', 5, _MD_XOOPSTUBE_STOPIT . '<br><img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/icon/security.png">');
            }
        }
    }
    $currentTime       = time();
    $timestamp30daysAgo       = ($currentTime - (86400 * 30));
    $timestamp7daysAgo  = ($currentTime - (86400 * 7));
    $allmonthvideos = 0;
    $allweekvideos  = 0;
    $result         = $GLOBALS['xoopsDB']->query(
        'SELECT lid, cid, published, updated FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE (published >= ' . $timestamp30daysAgo . ' AND published <= ' . $currentTime . ') OR updated >= ' . $timestamp30daysAgo . ' AND (expired = 0 OR expired > ' . $currentTime . ') AND offline = 0'
    );

    while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $published = ($myrow['updated'] > 0) ? $myrow['updated'] : $myrow['published'];
        ++$allmonthvideos;
        if ($published > $timestamp7daysAgo) {
            ++$allweekvideos;
        }
    }
    $xoopsTpl->assign('allweekvideos', $allweekvideos);
    $xoopsTpl->assign('allmonthvideos', $allmonthvideos);

    // List Last VARIABLE Days of videos
    //  $newvideoshowdays = cleanRequestVars($_REQUEST, 'newvideoshowdays', 7 );
    $newvideoshowdays = (int)Request::getInt('newvideoshowdays', 7, 'GET');
    $xoopsTpl->assign('newvideoshowdays', $newvideoshowdays);

    $dailyvideos = [];
    for ($i = 0; $i < $newvideoshowdays; ++$i) {
        $key                                 = $newvideoshowdays - $i - 1;
        $time                                = $currentTime - (86400 * $key);
        $dailyvideos[$key]['newvideodayRaw'] = $time;
        $dailyvideos[$key]['newvideoView']   = Utility::getTimestamp(formatTimestamp($time, $GLOBALS['xoopsModuleConfig']['dateformat']));
        $dailyvideos[$key]['totalvideos']    = 0;
    }
}

$timestamp30daysAgo = ($currentTime - (86400 * ($newvideoshowdays - 1)));
$sql =     'SELECT lid, cid, published, updated FROM '
           . $GLOBALS['xoopsDB']->prefix('xoopstube_videos')
           . ' WHERE (published > '
           . $timestamp30daysAgo
           . ' AND published <= '
           . $currentTime
           . ') OR (updated >= '
           . $timestamp30daysAgo
           . ' AND updated <= '
           . $currentTime
           . ') AND (expired = 0 OR expired > '
           . $currentTime
           . ') AND offline = 0';

$result   = $GLOBALS['xoopsDB']->query($sql);

while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
//    $published = ($myrow['updated'] > 0) ? date($GLOBALS['xoopsModuleConfig']['dateformat'], $myrow['updated']) : date($GLOBALS['xoopsModuleConfig']['dateformat'], $myrow['published']);
    $published = ($myrow['updated'] > 0) ? $myrow['updated'] : $myrow['published'];
    $d         = date('j', $published);
    $m         = date('m', $published);
    $y         = date('Y', $published);
    $key       = (int)($currentTime - mktime(0, 0, 0, $m, $d, $y) / 86400);

    $key2 = $published; //mb

    if (isset($dailyvideos[$key])) {
        $dailyvideos[$key]['totalvideos']++;
    }
}
ksort($dailyvideos);
reset($dailyvideos);
$xoopsTpl->assign('dailyvideos', $dailyvideos);

$xoopsTpl->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

unset($dailyvideos);

$mytree = new Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');
$sql    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos');
$sql    .= ' WHERE (published > 0 AND published <= ' . $currentTime . ')
         OR (updated > 0 AND updated <= ' . $currentTime . ')
         AND (expired = 0 OR expired > ' . $currentTime . ')
         AND offline = 0
         ORDER BY ' . $GLOBALS['xoopsModuleConfig']['linkxorder'];
$result = $GLOBALS['xoopsDB']->query($sql, 10, 0);
while (false !== ($videoArray = $GLOBALS['xoopsDB']->fetchArray($result))) {
    require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/videoloadinfo.php';
}

$xoopsTpl->assign('back', '<a href="javascript:history.go(-1)"><img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/icon/back.png"></a>');
$xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));
require_once XOOPS_ROOT_PATH . '/footer.php';
