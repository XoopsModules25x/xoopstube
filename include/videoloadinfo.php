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

use Xmf\Module\Admin;
use XoopsModules\Xoopstube\{
    Utility
};

// $module_video = '';

//require_once __DIR__ . '/admin_header.php';

//global $pathIcon16;
$myts = \MyTextSanitizer::getInstance(); // MyTextSanitizer object

$video['id']        = (int)$videoArray['lid'];
$video['cid']       = (int)$videoArray['cid'];
$video['published'] = (int)$videoArray['published'] ? true : false;
$cid = $video['cid'];

$path              = $mytree->getPathFromId($videoArray['cid'], 'title');
$path              = mb_substr($path, 1);
$path              = basename($path);
$path              = str_replace('/', '', $path);
$video['category'] = $path;

$rating           = round(number_format($videoArray['rating'], 0) / 2);
$video['rateimg'] = "rate$rating.png";
unset($rating);

$video['votes'] = (1 == $videoArray['votes']) ? _MD_XOOPSTUBE_ONEVOTE : sprintf(_MD_XOOPSTUBE_NUMVOTES, $videoArray['votes']);
$video['hits']  = sprintf(_MD_XOOPSTUBE_VIDEOHITS, (int)$videoArray['hits']);
$xoopsTpl->assign('lang_dltimes', $video['hits']);
$publisher           = (isset($videoArray['publisher']) && !empty($videoArray['publisher'])) ? htmlspecialchars($videoArray['publisher'], ENT_QUOTES | ENT_HTML5) : _MD_XOOPSTUBE_NOTSPECIFIED;
$video['title']      = htmlspecialchars($videoArray['title'], ENT_QUOTES | ENT_HTML5);
$video['vidid']      = $videoArray['vidid'];
$video['videothumb'] = xtubeGetVideoThumb($videoArray['vidid'], $videoArray['title'], $videoArray['vidsource'], $videoArray['picurl'], $GLOBALS['xoopsModuleConfig']['videoimgdir'] . '/' . $videoArray['screenshot']);
$video['publisher']  = xtubeGetVideoPublisher($videoArray['vidid'], $videoArray['publisher'], $videoArray['vidsource']);

if (empty($moderate)) {
    $time       = (0 !== $videoArray['updated']) ? $videoArray['updated'] : $videoArray['published'];
    $is_updated = (0 !== $videoArray['updated']) ? _MD_XOOPSTUBE_UPDATEDON : _MD_XOOPSTUBE_PUBLISHDATE;
    $xoopsTpl->assign('lang_subdate', $is_updated);
} else {
    $time       = $videoArray['date'];
    $is_updated = _MD_XOOPSTUBE_SUBMITDATE;
    $xoopsTpl->assign('lang_subdate', $is_updated);
}
$pathIcon16          = Admin::iconUrl('', 16);
$video['adminvideo'] = '';
$video['isadmin']    = ((is_object($GLOBALS['xoopsUser']) && !empty($GLOBALS['xoopsUser'])) && $GLOBALS['xoopsUser']->isAdmin($xoopsModule->mid()));
if (true === $video['isadmin'] &&  empty($moderate)) {
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
    if (200 == $videoArray['vidsource']) {
        $video['adminvideo'] .= '<a href="'
                                . XOOPS_URL
                                . '/modules/'
                                . $xoopsModule->getVar('dirname')
                                . '/admin/main.php?op=edit&amp;lid='
                                . $videoArray['lid']
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
                                . $videoArray['lid']
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
                            . $videoArray['lid']
                            . '"><img src="'
                            . $pathIcon16
                            . '/delete.png" alt="'
                            . _MD_XOOPSTUBE_DELETE
                            . '" title="'
                            . _MD_XOOPSTUBE_DELETE
                            . '" style="vertical-align: middle;"></a>';
} else {
    $video['adminvideo'] = '[ <a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/submit.php?op=edit&amp;lid=' . $videoArray['lid'] . '&approve=1">' . _MD_XOOPSTUBE_APPROVE . '</a> | ';
    $video['adminvideo'] .= '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/submit.php?op=delete&amp;lid=' . $videoArray['lid'] . '">' . _MD_XOOPSTUBE_DELETE . '</a> ]';
}
$votestring = (1 == $videoArray['votes']) ? _MD_XOOPSTUBE_ONEVOTE : sprintf(_MD_XOOPSTUBE_NUMVOTES, $videoArray['votes']);

$video['useradminvideo'] = 0;
if (is_object($GLOBALS['xoopsUser']) && !empty($GLOBALS['xoopsUser'])) {
    $_user_submitter = $GLOBALS['xoopsUser']->getVar('uid') === $videoArray['submitter'];
    if (true === Utility::checkGroups($cid)) {
        $video['useradminvideo'] = 1;
        if ($GLOBALS['xoopsUser']->getVar('uid') === $videoArray['submitter']) {
            $video['usermodify'] = '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/submit.php?lid=' . $videoArray['lid'] . '"> ' . _MD_XOOPSTUBE_MODIFY . '</a> |';
        }
    }
}

$description          = &$myts->displayTarea($videoArray['description'], 1, 1, 1, 1, 1);
$video['description'] = xoops_substr($description, 0, $GLOBALS['xoopsModuleConfig']['totalchars'], '...');

$video['updated']        = Utility::getTimestamp(formatTimestamp($time, $GLOBALS['xoopsModuleConfig']['dateformat']));
$video['submitter']      = Utility::getLinkedUserNameFromId($videoArray['submitter']);
$video['time']           = $videoArray['time'];
$video['mail_subject']   = rawurlencode(sprintf(_MD_XOOPSTUBE_INTFILEFOUND, $GLOBALS['xoopsConfig']['sitename']));
$video['mail_body']      = rawurlencode(sprintf(_MD_XOOPSTUBE_INTFILEFOUND, $GLOBALS['xoopsConfig']['sitename']) . ':  ' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $videoArray['cid'] . '&amp;lid=' . $videoArray['lid']);
$video['comments']       = $videoArray['comments'];
$video['icons']          = Utility::displayIcons($videoArray['published'], $videoArray['status'], $videoArray['hits']);
$video['allow_rating']   = Utility::checkGroups($cid, 'XTubeRatePerms') ? true : false;
$video['screen_shot']    = $GLOBALS['xoopsModuleConfig']['screenshot'];
$video['total_chars']    = $GLOBALS['xoopsModuleConfig']['totalchars'];
$video['module_dir']     = $xoopsModule->getVar('dirname');
$video['comment_rules']  = $GLOBALS['xoopsModuleConfig']['com_rule'];
$video['showrating']     = $GLOBALS['xoopsModuleConfig']['showrating'];
$video['showsubmitterx'] = $GLOBALS['xoopsModuleConfig']['showsubmitter'];
