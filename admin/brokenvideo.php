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

use Xmf\Request;
use XoopsModules\Xoopstube;

require_once __DIR__ . '/admin_header.php';

global $xtubeImageArray, $xoopsModule;

$op  = Request::getCmd('op', Request::getCmd('op', '', 'POST'), 'GET'); //cleanRequestVars($_REQUEST, 'op', '');
$lid = Request::getInt('lid', Request::getInt('lid', 0, 'POST'), 'GET'); //cleanRequestVars($_REQUEST, 'lid', 0);

switch (strtolower($op)) {
    case 'updatenotice':
        $ack         = Request::getInt('ack', 0); //cleanRequestVars($_REQUEST, 'ack', 0);
        $con         = Request::getInt('con', 1); //cleanRequestVars($_REQUEST, 'con', 1);
        $update_mess = '';

        if ($ack && !$con) {
            $acknowledged = (0 == $ack) ? 1 : 0;
            $sql          = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . ' SET acknowledged=' . $acknowledged;
            if (0 == $acknowledged) {
                $sql .= ', confirmed=0 ';
            }
            $sql .= ' WHERE lid=' . $lid;
            if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
                /** @var \XoopsLogger $logger */
                $logger = \XoopsLogger::getInstance();
                $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }
            $update_mess = _AM_XOOPSTUBE_BROKEN_NOWACK;
        } elseif (!$ack && !$con) {
            $acknowledged = (0 == $ack) ? 1 : 0;
            $sql          = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . ' SET acknowledged=' . $acknowledged;
            if (0 == $acknowledged) {
                $sql .= ', confirmed=0 ';
            }
            $sql .= ' WHERE lid=' . $lid;
            if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
                /** @var \XoopsLogger $logger */
                $logger = \XoopsLogger::getInstance();
                $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }

            $update_mess = _AM_XOOPSTUBE_BROKEN_NOWACK;
        }

        if ($con) {
            $confirmed = (0 == $con) ? 1 : 0;
            $sql       = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . ' SET confirmed=' . $confirmed;
            if (1 == $confirmed) {
                $sql .= ', acknowledged=' . $confirmed;
            }
            $sql .= ' WHERE lid=' . $lid;
            if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
                /** @var \XoopsLogger $logger */
                $logger = \XoopsLogger::getInstance();
                $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }
            $update_mess = _AM_XOOPSTUBE_BROKEN_NOWCON;
        } elseif (!$con) {
            $confirmed = (0 == $con) ? 1 : 0;
            $sql       = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . ' SET confirmed=' . $confirmed;
            if (1 == $confirmed) {
                $sql .= ', acknowledged=' . $confirmed;
            }
            $sql .= ' WHERE lid=' . $lid;
            if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
                /** @var \XoopsLogger $logger */
                $logger = \XoopsLogger::getInstance();
                $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }
            $update_mess = _AM_XOOPSTUBE_BROKEN_NOWCON;
        }
        redirect_header('brokenvideo.php?op=default', 1, $update_mess);
        break;

    case 'delbrokenvideos':
        $GLOBALS['xoopsDB']->queryF('DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . ' WHERE lid=' . $lid);
        $GLOBALS['xoopsDB']->queryF('DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid);
        $GLOBALS['xoopsDB']->queryF('DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_votedata') . ' WHERE lid=' . $lid);
        redirect_header('brokenvideo.php?op=default', 1, _AM_XOOPSTUBE_BROKENFILEDELETED);

        break;

    case 'ignorebrokenvideos':
        $GLOBALS['xoopsDB']->queryF('DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . ' WHERE lid=' . $lid);
        redirect_header('brokenvideo.php?op=default', 1, _AM_XOOPSTUBE_BROKEN_FILEIGNORED);
        break;

    default:
        $result            = $GLOBALS['xoopsDB']->query('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_broken') . ' ORDER BY reportid');
        $totalbrokenvideos = $GLOBALS['xoopsDB']->getRowsNum($result);

        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        echo '
        <fieldset style="border: #E8E8E8 1px solid;">
            <legend style="display: inline; font-weight: bold; color: #0A3760;">' . _AM_XOOPSTUBE_BROKEN_REPORTINFO . '</legend>
            <div style="padding: 8px;">' . _AM_XOOPSTUBE_BROKEN_REPORTSNO . '&nbsp;<b>' . $totalbrokenvideos . '</b><div>
            <div style="padding-left: 8px;">
           <ul>
                    <li>' . $xtubeImageArray['ignore'] . '&nbsp;&nbsp;' . _AM_XOOPSTUBE_BROKEN_IGNOREDESC . '</li>
                    <li>' . $xtubeImageArray['editimg'] . '&nbsp;&nbsp;' . _AM_XOOPSTUBE_BROKEN_EDITDESC . '</li>
                    <li>' . $xtubeImageArray['deleteimg'] . '&nbsp;&nbsp;' . _AM_XOOPSTUBE_BROKEN_DELETEDESC . '</li>
           </ul>
            </div>
        </fieldset>';

        echo '<table width="100%" border="0" cellspacing="1" cellpadding="2" class="outer" style="font-size: smaller;">';
        echo '<tr style="text-align: center;">';
        echo '<th width="3%" style="text-align: center;">' . _AM_XOOPSTUBE_BROKEN_ID . '</th>';
        echo '<th width="35%" style="text-align: left;">' . _AM_XOOPSTUBE_BROKEN_TITLE . '</th>';
        echo '<th>' . _AM_XOOPSTUBE_BROKEN_REPORTER . '</th>';
        echo '<th>' . _AM_XOOPSTUBE_BROKEN_FILESUBMITTER . '</th>';
        echo '<th>' . _AM_XOOPSTUBE_BROKEN_DATESUBMITTED . '</th>';
        echo '<th>' . _AM_XOOPSTUBE_BROKEN_ACKNOWLEDGED . '</th>';
        echo '<th>' . _AM_XOOPSTUBE_BROKEN_DCONFIRMED . '</th>';
        echo '<th style="text-align: center;">' . _AM_XOOPSTUBE_BROKEN_ACTION . '</th>';
        echo '</tr>';

        if (0 == $totalbrokenvideos) {
            echo '<tr style="text-align: center;"><td style="text-align: center;" class="head" colspan="8">' . _AM_XOOPSTUBE_BROKEN_NOFILEMATCH . '</td></tr>';
        } else {
            while (false !== (list($reportid, $lid, $sender, $ip, $date, $confirmed, $acknowledged) = $GLOBALS['xoopsDB']->fetchRow($result))) {
                $result2 = $GLOBALS['xoopsDB']->query('SELECT cid, title, vidid, submitter FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid);
                list($cid, $videoshowname, $vidid, $submitter) = $GLOBALS['xoopsDB']->fetchRow($result2);
                $email      = '';
                $sendername = '';

                if (0 !== $sender) {
                    $result3 = $GLOBALS['xoopsDB']->query('SELECT uname, email FROM ' . $GLOBALS['xoopsDB']->prefix('users') . ' WHERE uid=' . $sender);
                    list($sendername, $email) = $GLOBALS['xoopsDB']->fetchRow($result3);
                }
                $result4 = $GLOBALS['xoopsDB']->query('SELECT uname, email FROM ' . $GLOBALS['xoopsDB']->prefix('users') . '  WHERE uid=' . $sender);
                list($ownername, $owneremail) = $GLOBALS['xoopsDB']->fetchRow($result4);

                if ('' === $ownername) {
                    $ownername = '&nbsp;';
                }

                $ack_image = $acknowledged ? $xtubeImageArray['ack_yes'] : $xtubeImageArray['ack_no'];
                $con_image = $confirmed ? $xtubeImageArray['con_yes'] : $xtubeImageArray['con_no'];
                xoops_load('XoopsUserUtility');

                echo '<tr style="text-align: center;">';
                echo '<td class="head">' . $reportid . '</td>';
                echo '<td class="even" style="text-align: left;"><a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $cid . '&amp;lid=' . $lid . '" target="_blank">' . $videoshowname . '</a></td>';

                if ('' === $email) {
                    echo '<td class="even">' . XoopsUserUtility::getUnameFromId($sender) . ' (' . $ip . ')</td>';
                } else {
                    echo '<td class="even"><a href="mailto:' . $email . '">' . XoopsUserUtility::getUnameFromId($sender) . '</a> (' . $ip . ')</td>';
                }
                if ('' === $owneremail) {
                    echo '<td class="even">' . $ownername . '</td>';
                } else {
                    echo '<td class="even"><a href="mailto:' . $owneremail . '">' . $ownername . '</a></td>';
                }
                echo '<td class="even" style="text-align: center;">' . Xoopstube\Utility::getTimestamp(formatTimestamp($date, $GLOBALS['xoopsModuleConfig']['dateformatadmin'])) . '</td>';
                echo '<td class="even"><a href="brokenvideo.php?op=updateNotice&amp;lid=' . $lid . '&ack=' . (int)$acknowledged . '">' . $ack_image . ' </a></td>';
                echo '<td class="even"><a href="brokenvideo.php?op=updateNotice&amp;lid=' . $lid . '&con=' . (int)$confirmed . '">' . $con_image . '</a></td>';
                echo '<td class="even" style="text-align: center;" nowrap>';
                echo '<a href="brokenvideo.php?op=ignorebrokenvideos&amp;lid=' . $lid . '">' . $xtubeImageArray['ignore'] . '</a>&nbsp;';
                echo '<a href="main.php?op=edit&amp;lid=' . $lid . '">' . $xtubeImageArray['editimg'] . '</a>&nbsp;';
                echo '<a href="brokenvideo.php?op=delbrokenvideos&amp;lid=' . $lid . '">' . $xtubeImageArray['deleteimg'] . '</a>';
                echo '</td></tr>';
            }
        }
        echo '</table>';
}
require_once __DIR__ . '/admin_footer.php';
