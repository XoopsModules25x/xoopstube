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
 * @link            https://xoops.org/
 * @since           1.0.6
 */

use Xmf\Request;
use XoopsModules\Xoopstube\{
    Utility,
    Tree
};

$GLOBALS['xoopsOption']['template_main'] = 'xoopstube_singlevideo.tpl';

require_once __DIR__ . '/header.php';

$lid = Request::getInt('lid', Request::getInt('lid', '', 'POST'), 'GET');
$cid = Request::getInt('cid', Request::getInt('cid', '', 'POST'), 'GET');

$sql2 = 'SELECT count(*) FROM '
        . $GLOBALS['xoopsDB']->prefix('xoopstube_videos')
        . ' a LEFT JOIN '
        . $GLOBALS['xoopsDB']->prefix('xoopstube_altcat')
        . ' b'
        . ' ON b.lid = a.lid'
        . ' WHERE a.published > 0 AND a.published <= '
        . time()
        . ' AND (a.expired = 0 OR a.expired > '
        . time()
        . ') AND a.offline = 0'
        . ' AND (b.cid=a.cid OR (a.cid='
        . $cid
        . ' OR b.cid='
        . $cid
        . '))';
[$count] = $GLOBALS['xoopsDB']->fetchRow($GLOBALS['xoopsDB']->query($sql2));

if (false === Utility::checkGroups($cid) || 0 === $count) {
    redirect_header('index.php', 1, _MD_XOOPSTUBE_MUSTREGFIRST);
}

$sql       = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid . '
        AND (published > 0 AND published <= ' . time() . ')
        AND (expired = 0 OR expired > ' . time() . ')
        AND offline = 0
        AND cid > 0';
$result    = $GLOBALS['xoopsDB']->query($sql);
$videoArray = $GLOBALS['xoopsDB']->fetchArray($result);

if (!is_array($videoArray)) {
    redirect_header('index.php', 1, _MD_XOOPSTUBE_NOVIDEOLOAD);
}


require_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet('modules/' . $moduleDirName . '/assets/css/xtubestyle.css');

// tags support
if (Utility::isModuleTagInstalled()) {
    require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
    $xoopsTpl->assign('tagbar', tagBar($videoArray['lid'], 0));
}

$video['imageheader']  = Utility::renderImageHeader();
$video['id']           = $videoArray['lid'];
$video['cid']          = $videoArray['cid'];
$video['vidid']        = $videoArray['vidid'];
$video['description2'] = $myts->displayTarea($videoArray['description'], 1, 1, 1, 1, 1);

$mytree        = new Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');
$pathstring    = '<a href="index.php">' . _MD_XOOPSTUBE_MAIN . '</a>&nbsp;:&nbsp;';
$pathstring    .= $mytree->getNicePathFromId($cid, 'title', 'viewcat.php?op=');
$video['path'] = $pathstring;
// Get video from source
$video['showvideo'] = xtubeShowVideo($videoArray['vidid'], $videoArray['vidsource'], $videoArray['screenshot'], $videoArray['picurl']);

// Get Social Bookmarks
$video['sbmarks'] = getSocialBookmarks($videoArray['lid']);

// Start of meta tags
global $xoopsTpl, $xoTheme;

$maxWords = 100;
$words    = [];
$words    = explode(' ', Utility::convertHtml2text($videoArray['description']));
$newWords = [];
$i        = 0;
while ($i < $maxWords - 1 && $i < count($words)) {
    if (isset($words[$i])) {
        $newWords[] = trim($words[$i]);
    }
    ++$i;
}
$video_meta_description = implode(' ', $newWords);

if (is_object($GLOBALS['xoTheme'])) {
    if ($videoArray['keywords']) {
        $GLOBALS['xoTheme']->addMeta('meta', 'keywords', $videoArray['keywords']);
    }
    $GLOBALS['xoTheme']->addMeta('meta', 'title', $videoArray['title']);
    if (1 == $GLOBALS['xoopsModuleConfig']['usemetadescr']) {
        $GLOBALS['xoTheme']->addMeta('meta', 'description', $video_meta_description);
    }
} else {
    if ($videoArray['keywords']) {
        $xoopsTpl->assign('xoops_meta_keywords', $videoArray['keywords']);
    }
    if (1 == $GLOBALS['xoopsModuleConfig']['usemetadescr']) {
        $GLOBALS['xoTheme']->assign('xoops_meta_description', $video_meta_description);
    }
}
$xoopsTpl->assign('xoops_pagetitle', $videoArray['title']);
// End of meta tags

$moderate = 0;
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/videoloadinfo.php';

$xoopsTpl->assign('show_screenshot', false);
if (isset($GLOBALS['xoopsModuleConfig']['screenshot']) && 1 == $GLOBALS['xoopsModuleConfig']['screenshot']) {
    $xoopsTpl->assign('shotwidth', $GLOBALS['xoopsModuleConfig']['shotwidth']);
    $xoopsTpl->assign('shotheight', $GLOBALS['xoopsModuleConfig']['shotheight']);
    $xoopsTpl->assign('show_screenshot', true);
}

if (false === $video['isadmin']) {
    $count = Utility::updateCounter($lid);
}

// Show other author videos
$sql    = 'SELECT lid, cid, title, published FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . '
        WHERE submitter=' . $videoArray['submitter'] . '
        AND lid <> ' . $videoArray['lid'] . '
        AND published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time() . ')
        AND offline = 0 ORDER BY published DESC';
$result = $GLOBALS['xoopsDB']->query($sql, 10, 0);

while (false !== ($arr = $GLOBALS['xoopsDB']->fetchArray($result))) {
    if (true === Utility::checkGroups($arr['cid'])) {
        $videouid['title']     = htmlspecialchars($arr['title'], ENT_QUOTES | ENT_HTML5);
        $videouid['lid']       = $arr['lid'];
        $videouid['cid']       = $arr['cid'];
        $videouid['published'] = Utility::getTimestamp(formatTimestamp($arr['published'], $GLOBALS['xoopsModuleConfig']['dateformat']));
        $xoopsTpl->append('video_uid', $videouid);
    }
}

// Copyright notice
if (isset($GLOBALS['xoopsModuleConfig']['copyright']) && 1 == $GLOBALS['xoopsModuleConfig']['copyright']) {
    $xoopsTpl->assign('lang_copyright', '' . $video['publisher'] . ' &#0169; ' . _MD_XOOPSTUBE_COPYRIGHT . ' ' . formatTimestamp(time(), 'Y') . ' - ' . XOOPS_URL);
}

// Show other videos by submitter
if (isset($GLOBALS['xoopsModuleConfig']['othervideos']) && 1 == $GLOBALS['xoopsModuleConfig']['othervideos']) {
    $xoopsTpl->assign('other_videos', '<b>' . _MD_XOOPSTUBE_OTHERBYUID . '</b>' . $video['submitter'] . '<br>');
} else {
    $xoopsTpl->assign('other_videos', '<b>' . _MD_XOOPSTUBE_NOOTHERBYUID . '</b>' . $video['submitter'] . '<br>');
}

$video['showsubmitterx'] = $GLOBALS['xoopsModuleConfig']['showsubmitter'];
$video['showsbookmarx']  = $GLOBALS['xoopsModuleConfig']['showsbookmarks'];
$video['othervideox']    = $GLOBALS['xoopsModuleConfig']['othervideos'];
$xoopsTpl->assign('video', $video);

$xoopsTpl->assign('back', '<a href="javascript:history.go(-1)"><img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/icon/back.png"></a>'); // Displays Back button
$xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));
$xoopsTpl->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

require XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
