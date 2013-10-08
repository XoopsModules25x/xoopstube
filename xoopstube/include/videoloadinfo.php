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

// $module_video = '';

//include_once '/admin/admin_header.php';

global $pathIcon16;

$video['id']        = intval($video_arr['lid']);
$video['cid']       = intval($video_arr['cid']);
$video['published'] = intval($video_arr['published']) ? true : false;

$path              = $mytree->getPathFromId($video_arr['cid'], 'title');
$path              = substr($path, 1);
$path              = basename($path);
$path              = str_replace('/', '', $path);
$video['category'] = $path;

$rating           = round(number_format($video_arr['rating'], 0) / 2);
$video['rateimg'] = "rate$rating.png";
unset($rating);

$video['votes'] = ($video_arr['votes'] == 1) ? _MD_XTUBE_ONEVOTE : sprintf(_MD_XTUBE_NUMVOTES, $video_arr['votes']);
$video['hits']  = sprintf(_MD_XTUBE_VIDEOHITS, intval($video_arr['hits']));
$xoopsTpl->assign('lang_dltimes', $video['hits']);
$publisher           = (isset($video_arr['publisher'])
                        && !empty($video_arr['publisher'])) ? $xtubemyts->htmlSpecialCharsStrip(
    $video_arr['publisher']
) : _MD_XTUBE_NOTSPECIFIED;
$video['title']      = $xtubemyts->htmlSpecialCharsStrip($video_arr['title']);
$video['vidid']      = $video_arr['vidid'];
$video['videothumb'] = xtubeGetVideoThumb(
    $video_arr['vidid'],
    $video_arr['title'],
    $video_arr['vidsource'],
    $video_arr['picurl'],
    $xoopsModuleConfig['videoimgdir'] . '/' . $video_arr['screenshot']
);
$video['publisher']  = xtubeGetVideoPublisher($video_arr['vidid'], $video_arr['publisher'], $video_arr['vidsource']);

if ($moderate == 0) {
    $time       = ($video_arr['updated'] != 0) ? $video_arr['updated'] : $video_arr['published'];
    $is_updated = ($video_arr['updated'] != 0) ? _MD_XTUBE_UPDATEDON : _MD_XTUBE_PUBLISHDATE;
    $xoopsTpl->assign('lang_subdate', $is_updated);
} else {
    $time       = $video_arr['date'];
    $is_updated = _MD_XTUBE_SUBMITDATE;
    $xoopsTpl->assign('lang_subdate', $is_updated);
}

$video['adminvideo'] = '';
$video['isadmin']    = ((is_object($xoopsUser) && !empty($xoopsUser))
                        && $xoopsUser->isAdmin(
        $xoopsModule->mid()
    )) ? true : false;
if ($video['isadmin'] == true && $moderate == 0) {
    $video['adminvideo']
        = '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php"><img src="'
          . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/images/icon/computer.png" alt="'
          . _MD_XTUBE_ADMINSECTION . '" title="'
          . _MD_XTUBE_ADMINSECTION . '" style="vertical-align: middle;" /></a>&nbsp;';

    if ($video_arr['vidsource'] == 200) {
        $video['adminvideo']
            .=
            '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/main.php?op=edit&amp;lid='
            . $video_arr['lid'] . '"><img src="' . $xoopsModule->getInfo('icons16') . '/edit.png" alt="'
            . _MD_XTUBE_EDIT . '" title="' . _MD_XTUBE_EDIT . '" style="vertical-align: middle;" /></a>&nbsp;';
    } else {
        $video['adminvideo']
            .=
            '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/main.php?op=edit&amp;lid='
            . $video_arr['lid'] . '"><img src="' . $xoopsModule->getInfo('icons16') . '/edit.png" alt="'
            . _MD_XTUBE_EDIT . '" title="' . _MD_XTUBE_EDIT . '" style="vertical-align: middle;" /></a>&nbsp;';
    }
    $video['adminvideo']
        .=
        '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/main.php?op=delete&amp;lid='
        . $video_arr['lid'] . '"><img src="' . $xoopsModule->getInfo('icons16') . '/delete.png" alt="'
        . _MD_XTUBE_DELETE . '" title="' . _MD_XTUBE_DELETE . '" style="vertical-align: middle;" /></a>';
} else {
    $video['adminvideo']
        = '[ <a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/submit.php?op=edit&amp;lid='
          . $video_arr['lid'] . '&approve=1">' . _MD_XTUBE_APPROVE . '</a> | ';
    $video['adminvideo']
        .= '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/submit.php?op=delete&amp;lid='
           . $video_arr['lid'] . '">' . _MD_XTUBE_DELETE . '</a> ]';
}
$votestring = ($video_arr['votes'] == 1) ? _MD_XTUBE_ONEVOTE : sprintf(_MD_XTUBE_NUMVOTES, $video_arr['votes']);

$video['useradminvideo'] = 0;
if (is_object($xoopsUser) && !empty($xoopsUser)) {
    $_user_submitter = ($xoopsUser->getvar('uid') == $video_arr['submitter']) ? true : false;
    if (true == xtubeCheckGroups($cid)) {
        $video['useradminvideo'] = 1;
        if ($xoopsUser->getvar('uid') == $video_arr['submitter']) {
            $video['usermodify']
                = '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/submit.php?lid='
                  . $video_arr['lid'] . '"> ' . _MD_XTUBE_MODIFY . '</a> |';
        }
    }
}

$description          = $xtubemyts->displayTarea($video_arr['description'], 1, 1, 1, 1, 1);
$video['description'] = xoops_substr($description, 0, $xoopsModuleConfig['totalchars'], '...');

$video['updated']        = xtubeGetTimestamp(formatTimestamp($time, $xoopsModuleConfig['dateformat']));
$video['submitter']      = xtubeGetLinkedUserNameFromId($video_arr['submitter']);
$video['time']           = $video_arr['time'];
$video['mail_subject']   = rawurlencode(sprintf(_MD_XTUBE_INTFILEFOUND, $xoopsConfig['sitename']));
$video['mail_body']      = rawurlencode(
    sprintf(_MD_XTUBE_INTFILEFOUND, $xoopsConfig['sitename']) . ':  ' . XOOPS_URL . '/modules/' . $xoopsModule->getVar(
        'dirname'
    ) . '/singlevideo.php?cid=' . $video_arr['cid'] . '&amp;lid=' . $video_arr['lid']
);
$video['comments']       = $video_arr['comments'];
$video['icons']          = xtubeDisplayIcons($video_arr['published'], $video_arr['status'], $video_arr['hits']);
$video['allow_rating']   = (xtubeCheckGroups($cid, 'XTubeRatePerms')) ? true : false;
$video['screen_shot']    = $xoopsModuleConfig['screenshot'];
$video['total_chars']    = $xoopsModuleConfig['totalchars'];
$video['module_dir']     = $xoopsModule->getVar('dirname');
$video['comment_rules']  = $xoopsModuleConfig['com_rule'];
$video['showrating']     = $xoopsModuleConfig['showrating'];
$video['showsubmitterx'] = $xoopsModuleConfig['showsubmitter'];
