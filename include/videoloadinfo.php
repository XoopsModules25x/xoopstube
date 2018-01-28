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
 */

use XoopsModules\Xoopstube;

// $module_video = '';

//require_once __DIR__ . '/admin_header.php';

//global $pathIcon16;
$xtubemyts = new Xoopstube\TextSanitizer(); // MyTextSanitizer object

$video['id']        = (int)$video_arr['lid'];
$video['cid']       = (int)$video_arr['cid'];
$video['published'] = (int)$video_arr['published'] ? true : false;

$path              = $mytree->getPathFromId($video_arr['cid'], 'title');
$path              = substr($path, 1);
$path              = basename($path);
$path              = str_replace('/', '', $path);
$video['category'] = $path;

$rating           = round(number_format($video_arr['rating'], 0) / 2);
$video['rateimg'] = "rate$rating.png";
unset($rating);

$video['votes'] = (1 == $video_arr['votes']) ? _MD_XOOPSTUBE_ONEVOTE : sprintf(_MD_XOOPSTUBE_NUMVOTES, $video_arr['votes']);
$video['hits']  = sprintf(_MD_XOOPSTUBE_VIDEOHITS, (int)$video_arr['hits']);
$xoopsTpl->assign('lang_dltimes', $video['hits']);
$publisher           = (isset($video_arr['publisher']) && !empty($video_arr['publisher'])) ? $xtubemyts->htmlSpecialCharsStrip($video_arr['publisher']) : _MD_XOOPSTUBE_NOTSPECIFIED;
$video['title']      = $xtubemyts->htmlSpecialCharsStrip($video_arr['title']);
$video['vidid']      = $video_arr['vidid'];
$video['videothumb'] = xtubeGetVideoThumb($video_arr['vidid'], $video_arr['title'], $video_arr['vidsource'], $video_arr['picurl'], $GLOBALS['xoopsModuleConfig']['videoimgdir'] . '/' . $video_arr['screenshot']);
$video['publisher']  = xtubeGetVideoPublisher($video_arr['vidid'], $video_arr['publisher'], $video_arr['vidsource']);

if (0 == $moderate) {
    $time       = (0 !== $video_arr['updated']) ? $video_arr['updated'] : $video_arr['published'];
    $is_updated = (0 !== $video_arr['updated']) ? _MD_XOOPSTUBE_UPDATEDON : _MD_XOOPSTUBE_PUBLISHDATE;
    $xoopsTpl->assign('lang_subdate', $is_updated);
} else {
    $time       = $video_arr['date'];
    $is_updated = _MD_XOOPSTUBE_SUBMITDATE;
    $xoopsTpl->assign('lang_subdate', $is_updated);
}
$pathIcon16          = \Xmf\Module\Admin::iconUrl('', 16);
$video['adminvideo'] = '';
$video['isadmin']    = ((is_object($GLOBALS['xoopsUser']) && !empty($GLOBALS['xoopsUser'])) && $GLOBALS['xoopsUser']->isAdmin($xoopsModule->mid()));
if (true === $video['isadmin'] && 0 == $moderate) {
    $video['adminvideo'] = '<a href="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/admin/index.php"><img src="'
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->getVar('dirname')
                           . '/assets/images/icon/computer.png" alt="'
                           . _MD_XOOPSTUBE_ADMINSECTION
                           . '" title="'
                           . _MD_XOOPSTUBE_ADMINSECTION
                           . '" style="vertical-align: middle;"></a>&nbsp;';
    if (200 == $video_arr['vidsource']) {
        $video['adminvideo'] .= '<a href="'
                                . XOOPS_URL
                                . '/modules/'
                                . $xoopsModule->getVar('dirname')
                                . '/admin/main.php?op=edit&amp;lid='
                                . $video_arr['lid']
                                . '"><img src="'
                                . $pathIcon16
                                . '/edit.png" alt="'
                                . _MD_XOOPSTUBE_EDIT
                                . '" title="'
                                . _MD_XOOPSTUBE_EDIT
                                . '" style="vertical-align: middle;"></a>&nbsp;';
    } else {
        $video['adminvideo'] .= '<a href="'
                                . XOOPS_URL
                                . '/modules/'
                                . $xoopsModule->getVar('dirname')
                                . '/admin/main.php?op=edit&amp;lid='
                                . $video_arr['lid']
                                . '"><img src="'
                                . $pathIcon16
                                . '/edit.png" alt="'
                                . _MD_XOOPSTUBE_EDIT
                                . '" title="'
                                . _MD_XOOPSTUBE_EDIT
                                . '" style="vertical-align: middle;"></a>&nbsp;';
    }
    $video['adminvideo'] .= '<a href="'
                            . XOOPS_URL
                            . '/modules/'
                            . $xoopsModule->getVar('dirname')
                            . '/admin/main.php?op=delete&amp;lid='
                            . $video_arr['lid']
                            . '"><img src="'
                            . $pathIcon16
                            . '/delete.png" alt="'
                            . _MD_XOOPSTUBE_DELETE
                            . '" title="'
                            . _MD_XOOPSTUBE_DELETE
                            . '" style="vertical-align: middle;"></a>';
} else {
    $video['adminvideo'] = '[ <a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/submit.php?op=edit&amp;lid=' . $video_arr['lid'] . '&approve=1">' . _MD_XOOPSTUBE_APPROVE . '</a> | ';
    $video['adminvideo'] .= '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/submit.php?op=delete&amp;lid=' . $video_arr['lid'] . '">' . _MD_XOOPSTUBE_DELETE . '</a> ]';
}
$votestring = (1 == $video_arr['votes']) ? _MD_XOOPSTUBE_ONEVOTE : sprintf(_MD_XOOPSTUBE_NUMVOTES, $video_arr['votes']);

$video['useradminvideo'] = 0;
if (is_object($GLOBALS['xoopsUser']) && !empty($GLOBALS['xoopsUser'])) {
    $_user_submitter = $GLOBALS['xoopsUser']->getVar('uid') === $video_arr['submitter'];
    if (true === Xoopstube\Utility::checkGroups($cid)) {
        $video['useradminvideo'] = 1;
        if ($GLOBALS['xoopsUser']->getVar('uid') === $video_arr['submitter']) {
            $video['usermodify'] = '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/submit.php?lid=' . $video_arr['lid'] . '"> ' . _MD_XOOPSTUBE_MODIFY . '</a> |';
        }
    }
}

$description          =& $xtubemyts->displayTarea($video_arr['description'], 1, 1, 1, 1, 1);
$video['description'] = xoops_substr($description, 0, $GLOBALS['xoopsModuleConfig']['totalchars'], '...');

$video['updated']        = Xoopstube\Utility::getTimestamp(formatTimestamp($time, $GLOBALS['xoopsModuleConfig']['dateformat']));
$video['submitter']      = Xoopstube\Utility::getLinkedUserNameFromId($video_arr['submitter']);
$video['time']           = $video_arr['time'];
$video['mail_subject']   = rawurlencode(sprintf(_MD_XOOPSTUBE_INTFILEFOUND, $GLOBALS['xoopsConfig']['sitename']));
$video['mail_body']      = rawurlencode(sprintf(_MD_XOOPSTUBE_INTFILEFOUND, $GLOBALS['xoopsConfig']['sitename']) . ':  ' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $video_arr['cid'] . '&amp;lid=' . $video_arr['lid']);
$video['comments']       = $video_arr['comments'];
$video['icons']          = Xoopstube\Utility::displayIcons($video_arr['published'], $video_arr['status'], $video_arr['hits']);
$video['allow_rating']   = Xoopstube\Utility::checkGroups($cid, 'XTubeRatePerms') ? true : false;
$video['screen_shot']    = $GLOBALS['xoopsModuleConfig']['screenshot'];
$video['total_chars']    = $GLOBALS['xoopsModuleConfig']['totalchars'];
$video['module_dir']     = $xoopsModule->getVar('dirname');
$video['comment_rules']  = $GLOBALS['xoopsModuleConfig']['com_rule'];
$video['showrating']     = $GLOBALS['xoopsModuleConfig']['showrating'];
$video['showsubmitterx'] = $GLOBALS['xoopsModuleConfig']['showsubmitter'];
