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
    Utility
};

require_once __DIR__ . '/header.php';

$op  = Request::getCmd('op', Request::getCmd('op', '', 'POST'), 'GET');
$lid = Request::getInt('lid', Request::getInt('lid', '', 'POST'), 'GET');

$buttonn = mb_strtolower(_MD_XOOPSTUBE_SUBMITBROKEN);

switch (mb_strtolower($op)) {
    case $buttonn:
        $sender = (is_object($GLOBALS['xoopsUser']) && !empty($GLOBALS['xoopsUser'])) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
        $ip     = getenv('REMOTE_ADDR');
        $title  = Request::getString('title', '', 'POST');
        $time   = time();

        // Check if REG user is trying to report twice
        $result = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . ' WHERE lid=' . $lid);
        [$count] = $GLOBALS['xoopsDB']->fetchRow($result);
        if ($count > 0) {
            $ratemessage = _MD_XOOPSTUBE_ALREADYREPORTED;
            redirect_header('singlevideo.php?cid=' . (int)$cid . '&amp;lid=' . $lid, 2, $ratemessage);
        } else {
            $reportid = 0;
            $sql      = sprintf(
                'INSERT INTO `%s` (reportid, lid, sender, ip, date, confirmed, acknowledged, title ) VALUES ( %u, %u, %u, %s, %u, %u, %u, %s)',
                $GLOBALS['xoopsDB']->prefix('xoopstube_broken'),
                $reportid,
                $lid,
                $sender,
                $GLOBALS['xoopsDB']->quoteString($ip),
                $time,
                0,
                0,
                $GLOBALS['xoopsDB']->quoteString($title)
            );
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                $error[] = _MD_XOOPSTUBE_ERROR;
            }
            $newid = $GLOBALS['xoopsDB']->getInsertId();

            // Send notifications
            $tags                      = [];
            $tags['BROKENREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/main.php?op=listBrokenvideos';
            /** @var \XoopsNotificationHandler $notificationHandler */
            $notificationHandler = xoops_getHandler('notification');
            $notificationHandler->triggerEvent('global', 0, 'video_broken', $tags);

            // Send email to the owner of the linkload stating that it is broken
            $sql       = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid . ' AND published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time() . ')';
            $videoArray = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));
            unset($sql);

            /** @var \XoopsMemberHandler $memberHandler */
            $memberHandler = xoops_getHandler('member');
            $submit_user   = $memberHandler->getUser($videoArray['submitter']);
            if (is_object($submit_user) && null !== $submit_user) {
                $subdate = formatTimestamp($videoArray['date'], $GLOBALS['xoopsModuleConfig']['dateformat']);
                $cid     = $videoArray['cid'];
                $title   = htmlspecialchars($videoArray['title'], ENT_QUOTES | ENT_HTML5);
                $subject = _MD_XOOPSTUBE_BROKENREPORTED;

                $xoopsMailer = xoops_getMailer();
                $xoopsMailer->useMail();
                $templateDir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/mail_template';
                $xoopsMailer->setTemplateDir($templateDir);
                $xoopsMailer->setTemplate('videobroken_notify.tpl');
                $xoopsMailer->setToEmails($submit_user->getVar('email'));
                $xoopsMailer->setFromEmail($GLOBALS['xoopsConfig']['adminmail']);
                $xoopsMailer->setFromName($GLOBALS['xoopsConfig']['sitename']);
                $xoopsMailer->assign('X_UNAME', $submit_user->getVar('uname'));
                $xoopsMailer->assign('SITENAME', $GLOBALS['xoopsConfig']['sitename']);
                $xoopsMailer->assign('X_ADMINMAIL', $GLOBALS['xoopsConfig']['adminmail']);
                $xoopsMailer->assign('X_SITEvidid', _AM_XOOPSTUBE_MOD_VIDID . '/');
                $xoopsMailer->assign('X_TITLE', $title);
                $xoopsMailer->assign('X_SUB_DATE', $subdate);
                $xoopsMailer->assign('X_VIDEOLOAD', XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $cid . '&amp;lid=' . $lid);
                $xoopsMailer->setSubject($subject);
                $message = $xoopsMailer->send() ? _MD_XOOPSTUBE_BROKENREPORTED : _MD_XOOPSTUBE_ERRORSENDEMAIL;
            } else {
                $message = _MD_XOOPSTUBE_ERRORSENDEMAIL;
            }
            redirect_header('singlevideo.php?cid=' . (int)$cid . '&amp;lid=' . $lid, 2, $message);
        }
        break;
    default:

        $GLOBALS['xoopsOption']['template_main'] = 'xoopstube_brokenvideo.tpl';
        require_once XOOPS_ROOT_PATH . '/header.php';

        $catarray['imageheader'] = Utility::renderImageHeader();
        $xoopsTpl->assign('catarray', $catarray);

        $sql       = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid;
        $videoArray = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));
        unset($sql);

        $sql       = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . ' WHERE lid=' . $lid;
        $brokeArray = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));
        xoops_load('XoopsUserUtility');
        if (is_array($brokeArray)) {
            $broken['title']        = htmlspecialchars($videoArray['title'], ENT_QUOTES | ENT_HTML5);
            $broken['id']           = $brokeArray['reportid'];
            $broken['reporter']     = \XoopsUserUtility::getUnameFromId($brokeArray['sender']);
            $broken['date']         = Utility::getTimestamp(formatTimestamp($brokeArray['date'], $GLOBALS['xoopsModuleConfig']['dateformat']));
            $broken['acknowledged'] = (1 == $brokeArray['acknowledged']) ? _YES : _NO;
            $broken['confirmed']    = (1 == $brokeArray['confirmed']) ? _YES : _NO;
            $xoopsTpl->assign('broken', $broken);
            $xoopsTpl->assign('brokenreport', true);
        } else {
            if (!is_array($videoArray) || empty($videoArray)) {
                $ratemessage = _MD_XOOPSTUBE_THISFILEDOESNOTEXIST;
                redirect_header('singlevideo.php?cid=' . (int)$cid . '&amp;lid=' . $lid, 0, $ratemessage);
            }

            // file info
            $video['title']   = htmlspecialchars($videoArray['title'], ENT_QUOTES | ENT_HTML5);
            $time             = ($videoArray['published'] > 0) ? $videoArray['published'] : $link_arr['updated'];
            $video['updated'] = Utility::getTimestamp(formatTimestamp($time, $GLOBALS['xoopsModuleConfig']['dateformat']));
            $isUpdated       = (0 !== $videoArray['updated']) ? _MD_XOOPSTUBE_UPDATEDON : _MD_XOOPSTUBE_SUBMITDATE;

            $video['publisher'] = \XoopsUserUtility::getUnameFromId($videoArray['submitter']);

            $xoopsTpl->assign('video_id', $lid);
            $xoopsTpl->assign('lang_subdate', $isUpdated);
            $xoopsTpl->assign('video', $video);
        }

        Utility::setNoIndexNoFollow();

        $xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));
        require_once XOOPS_ROOT_PATH . '/footer.php';
        break;
}
