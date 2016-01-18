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
 * @link            http://sourceforge.net/projects/xoops/
 * @since           1.0.6
 * @version         $Id$
 */

require __DIR__ . '/header.php';

$op  = xtubeCleanRequestVars($_REQUEST, 'op', '');
$lid = xtubeCleanRequestVars($_REQUEST, 'lid', 0);
$lid = intval($lid);

$buttonn = strtolower(_MD_XOOPSTUBE_SUBMITBROKEN);

switch (strtolower($op)) {
    case $buttonn:
        global $xoopsUser;

        $sender = (is_object($xoopsUser) && !empty($xoopsUser)) ? $xoopsUser->getVar('uid') : 0;
        $ip     = getenv('REMOTE_ADDR');
        $title  = xtubeCleanRequestVars($_REQUEST, 'title', '');
        $title  = $xtubemyts->addslashes($title);
        $time   = time();

        // Check if REG user is trying to report twice
        $result = $xoopsDB->query(
            'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xoopstube_broken') . ' WHERE lid=' . intval($lid)
        );
        list ($count) = $xoopsDB->fetchRow($result);
        if ($count > 0) {
            $ratemessage = _MD_XOOPSTUBE_ALREADYREPORTED;
            redirect_header('singlevideo.php?cid=' . intval($cid) . '&amp;lid=' . intval($lid), 2, $ratemessage);
            exit();
        } else {
            $reportid = 0;
            $sql      = sprintf(
                "INSERT INTO %s (reportid, lid, sender, ip, date, confirmed, acknowledged, title ) VALUES ( %u, %u, %u, %s, %u, %u, %u, %s)",
                $xoopsDB->prefix('xoopstube_broken'),
                $reportid,
                $lid,
                $sender,
                $xoopsDB->quoteString($ip),
                $time,
                0,
                0,
                $xoopsDB->quoteString($title)
            );
            if (!$result = $xoopsDB->query($sql)) {
                $error[] = _MD_XOOPSTUBE_ERROR;
            }
            $newid = $xoopsDB->getInsertId();

            // Send notifications
            $tags = array();
            $tags['BROKENREPORTS_URL']
                                  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/main.php?op=listBrokenvideos';
            $notification_handler = & xoops_gethandler('notification');
            $notification_handler->triggerEvent('global', 0, 'video_broken', $tags);

            // Send email to the owner of the linkload stating that it is broken
            $sql
                       =
                'SELECT * FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE lid=' . intval($lid) . ' AND published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time()
                . ')';
            $video_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));
            unset($sql);

            $member_handler = & xoops_gethandler('member');
            $submit_user    = & $member_handler->getUser($video_arr['submitter']);
            if (is_object($submit_user) && !empty($submit_user)) {
                $subdate = formatTimestamp($video_arr['date'], $xoopsModuleConfig['dateformat']);
                $cid     = $video_arr['cid'];
                $title   = $xtubemyts->htmlSpecialCharsStrip($video_arr['title']);
                $subject = _MD_XOOPSTUBE_BROKENREPORTED;

                $xoopsMailer = & getMailer();
                $xoopsMailer->useMail();
                $template_dir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/mail_template';
                $xoopsMailer->setTemplateDir($template_dir);
                $xoopsMailer->setTemplate('videobroken_notify.tpl');
                $xoopsMailer->setToEmails($submit_user->getVar('email'));
                $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
                $xoopsMailer->setFromName($xoopsConfig['sitename']);
                $xoopsMailer->assign("X_UNAME", $submit_user->getVar('uname'));
                $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename']);
                $xoopsMailer->assign("X_ADMINMAIL", $xoopsConfig['adminmail']);
                $xoopsMailer->assign('X_SITEvidid', XOOPS_VIDID . '/');
                $xoopsMailer->assign("X_TITLE", $title);
                $xoopsMailer->assign("X_SUB_DATE", $subdate);
                $xoopsMailer->assign(
                    'X_VIDEOLOAD',
                    XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $cid . '&amp;lid=' . $lid
                );
                $xoopsMailer->setSubject($subject);
                $message = ($xoopsMailer->send()) ? _MD_XOOPSTUBE_BROKENREPORTED : _MD_XOOPSTUBE_ERRORSENDEMAIL;
            } else {
                $message = _MD_XOOPSTUBE_ERRORSENDEMAIL;
            }
            redirect_header('singlevideo.php?cid=' . intval($cid) . '&amp;lid=' . intval($lid), 2, $message);
        }
        break;

    default:

        $xoopsOption['template_main'] = 'xoopstube_brokenvideo.tpl';
        include XOOPS_ROOT_PATH . '/header.php';

        $catarray['imageheader'] = xtubeRenderImageHeader();
        $xoopsTpl->assign('catarray', $catarray);

        $sql       = 'SELECT * FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE lid=' . intval($lid);
        $video_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));
        unset($sql);

        $sql       = 'SELECT * FROM ' . $xoopsDB->prefix('xoopstube_broken') . ' WHERE lid=' . intval($lid);
        $broke_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));
        xoops_load('XoopsUserUtility');
        if (is_array($broke_arr)) {
            $broken['title']        = $xtubemyts->htmlSpecialCharsStrip($video_arr['title']);
            $broken['id']           = $broke_arr['reportid'];
            $broken['reporter']     = XoopsUserUtility::getUnameFromId($broke_arr['sender']);
            $broken['date']         = xoopstube(formatTimestamp($broke_arr['date'], $xoopsModuleConfig['dateformat']));
            $broken['acknowledged'] = ($broke_arr['acknowledged'] == 1) ? _YES : _NO;
            $broken['confirmed']    = ($broke_arr['confirmed'] == 1) ? _YES : _NO;
            $xoopsTpl->assign('broken', $broken);
            $xoopsTpl->assign('brokenreport', true);
        } else {
            if (!is_array($video_arr) || empty($video_arr)) {
                $ratemessage = _MD_XOOPSTUBE_THISFILEDOESNOTEXIST;
                redirect_header('singlevideo.php?cid=' . intval($cid) . '&amp;lid=' . intval($lid), 0, $ratemessage);
                exit();
            }

            // file info
            $video['title']   = $xtubemyts->htmlSpecialCharsStrip($video_arr['title']);
            $time             = ($video_arr['published'] > 0) ? $video_arr['published'] : $link_arr['updated'];
            $video['updated'] = xtubeGetTimestamp(formatTimestamp($time, $xoopsModuleConfig['dateformat']));
            $is_updated       = ($video_arr['updated'] != 0) ? _MD_XOOPSTUBE_UPDATEDON : _MD_XOOPSTUBE_SUBMITDATE;

            $video['publisher'] = XoopsUserUtility::getUnameFromId($video_arr['submitter']);

            $xoopsTpl->assign('video_id', intval($lid));
            $xoopsTpl->assign('lang_subdate', $is_updated);
            $xoopsTpl->assign('video', $video);
        }

        xtubeSetNoIndexNoFollow();

        $xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));
        include XOOPS_ROOT_PATH . '/footer.php';
        break;
}
