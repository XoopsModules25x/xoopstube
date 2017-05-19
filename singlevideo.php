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

include __DIR__ . '/header.php';

$lid = XoopsRequest::getInt('lid', XoopsRequest::getInt('lid', '', 'POST'), 'GET');
$cid = XoopsRequest::getInt('cid', XoopsRequest::getInt('cid', '', 'POST'), 'GET');

$sql2 = 'SELECT count(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' a LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('xoopstube_altcat') . ' b' . ' ON b.lid = a.lid' . ' WHERE a.published > 0 AND a.published <= ' . time() . ' AND (a.expired = 0 OR a.expired > ' . time()
        . ') AND a.offline = 0' . ' AND (b.cid=a.cid OR (a.cid=' . (int)$cid . ' OR b.cid=' . (int)$cid . '))';
list($count) = $GLOBALS['xoopsDB']->fetchRow($GLOBALS['xoopsDB']->query($sql2));

if (false === XoopstubeUtility::xtubeCheckGroups($cid) || $count === 0) {
    redirect_header('index.php', 1, _MD_XOOPSTUBE_MUSTREGFIRST);
}

$sql       = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . (int)$lid . '
        AND (published > 0 AND published <= ' . time() . ')
        AND (expired = 0 OR expired > ' . time() . ')
        AND offline = 0
        AND cid > 0';
$result    = $GLOBALS['xoopsDB']->query($sql);
$video_arr = $GLOBALS['xoopsDB']->fetchArray($result);

if (!is_array($video_arr)) {
    redirect_header('index.php', 1, _MD_XOOPSTUBE_NOVIDEOLOAD);
}

$GLOBALS['xoopsOption']['template_main'] = 'xoopstube_singlevideo.tpl';

include XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet('modules/'.$moduleDirName.'/assets/css/xtubestyle.css');

$xoTheme->addStylesheet('modules/'.$moduleDirName.'/assets/css/xtubestyle.css');

// tags support
if (XoopstubeUtility::xtubeIsModuleTagInstalled()) {
    require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
    $xoopsTpl->assign('tagbar', tagBar($video_arr['lid'], 0));
}

$video['imageheader']  = XoopstubeUtility::xtubeRenderImageHeader();
$video['id']           = $video_arr['lid'];
$video['cid']          = $video_arr['cid'];
$video['vidid']        = $video_arr['vidid'];
$video['description2'] = $xtubemyts->displayTarea($video_arr['description'], 1, 1, 1, 1, 1);

$mytree        = new XoopstubeTree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');
$pathstring    = '<a href="index.php">' . _MD_XOOPSTUBE_MAIN . '</a>&nbsp;:&nbsp;';
$pathstring    .= $mytree->getNicePathFromId($cid, 'title', 'viewcat.php?op=');
$video['path'] = $pathstring;
// Get video from source
$video['showvideo'] = xtubeShowVideo($video_arr['vidid'], $video_arr['vidsource'], $video_arr['screenshot'], $video_arr['picurl']);

// Get Social Bookmarks
$video['sbmarks'] = getSocialBookmarks($video_arr['lid']);

// Start of meta tags
global $xoopsTpl, $xoTheme;

$maxWords = 100;
$words    = array();
$words    = explode(' ', XoopstubeUtility::xtubeConvertHtml2Text($video_arr['description']));
$newWords = array();
$i        = 0;
while ($i < $maxWords - 1 && $i < count($words)) {
    if (isset($words[$i])) {
        $newWords[] = trim($words[$i]);
    }
    ++$i;
}
$video_meta_description = implode(' ', $newWords);

if (is_object($GLOBALS['xoTheme'])) {
    if ($video_arr['keywords']) {
        $GLOBALS['xoTheme']->addMeta('meta', 'keywords', $video_arr['keywords']);
    }
    $GLOBALS['xoTheme']->addMeta('meta', 'title', $video_arr['title']);
    if ($GLOBALS['xoopsModuleConfig']['usemetadescr'] == 1) {
        $GLOBALS['xoTheme']->addMeta('meta', 'description', $video_meta_description);
    }
} else {
    if ($video_arr['keywords']) {
        $xoopsTpl->assign('xoops_meta_keywords', $video_arr['keywords']);
    }
    if ($GLOBALS['xoopsModuleConfig']['usemetadescr'] == 1) {
        $GLOBALS['xoTheme']->assign('xoops_meta_description', $video_meta_description);
    }
}
$xoopsTpl->assign('xoops_pagetitle', $video_arr['title']);
// End of meta tags

$moderate = 0;
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/videoloadinfo.php';

$xoopsTpl->assign('show_screenshot', false);
if (isset($GLOBALS['xoopsModuleConfig']['screenshot']) && $GLOBALS['xoopsModuleConfig']['screenshot'] == 1) {
    $xoopsTpl->assign('shotwidth', $GLOBALS['xoopsModuleConfig']['shotwidth']);
    $xoopsTpl->assign('shotheight', $GLOBALS['xoopsModuleConfig']['shotheight']);
    $xoopsTpl->assign('show_screenshot', true);
}

if (false === $video['isadmin']) {
    $count = XoopstubeUtility::xtubeUpdateCounter($lid);
}

// Show other author videos
$sql    = 'SELECT lid, cid, title, published FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . '
        WHERE submitter=' . $video_arr['submitter'] . '
        AND lid <> ' . $video_arr['lid'] . '
        AND published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time() . ')
        AND offline = 0 ORDER BY published DESC';
$result = $GLOBALS['xoopsDB']->query($sql, 10, 0);

while (false !== ($arr = $GLOBALS['xoopsDB']->fetchArray($result))) {
    if (true === XoopstubeUtility::xtubeCheckGroups($arr['cid'])) {
        $videouid['title']     = $xtubemyts->htmlSpecialCharsStrip($arr['title']);
        $videouid['lid']       = $arr['lid'];
        $videouid['cid']       = $arr['cid'];
        $videouid['published'] = XoopstubeUtility::xtubeGetTimestamp(formatTimestamp($arr['published'], $GLOBALS['xoopsModuleConfig']['dateformat']));
        $xoopsTpl->append('video_uid', $videouid);
    }
}

// Copyright notice
if (isset($GLOBALS['xoopsModuleConfig']['copyright']) && $GLOBALS['xoopsModuleConfig']['copyright'] == 1) {
    $xoopsTpl->assign('lang_copyright', '' . $video['publisher'] . ' &#0169; ' . _MD_XOOPSTUBE_COPYRIGHT . ' ' . formatTimestamp(time(), 'Y') . ' - ' . XOOPS_URL);
}

// Show other videos by submitter
if (isset($GLOBALS['xoopsModuleConfig']['othervideos']) && $GLOBALS['xoopsModuleConfig']['othervideos'] == 1) {
    $xoopsTpl->assign('other_videos', '<b>' . _MD_XOOPSTUBE_OTHERBYUID . '</b>' . $video['submitter'] . '<br>');
} else {
    $xoopsTpl->assign('other_videos', '<b>' . _MD_XOOPSTUBE_NOOTHERBYUID . '</b>' . $video['submitter'] . '<br>');
}

$video['showsubmitterx'] = $GLOBALS['xoopsModuleConfig']['showsubmitter'];
$video['showsbookmarx']  = $GLOBALS['xoopsModuleConfig']['showsbookmarks'];
$video['othervideox']    = $GLOBALS['xoopsModuleConfig']['othervideos'];
$xoopsTpl->assign('video', $video);

$xoopsTpl->assign('back', '<a href="javascript:history.go(-1)"><img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/icon/back.png" /></a>'); // Displays Back button
$xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));

require_once XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
