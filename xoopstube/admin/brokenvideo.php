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

include 'admin_header.php';

global $xtubeImageArray, $xoopsModule, $xoopsDB;

$op  = xtubeCleanRequestVars($_REQUEST, 'op', '');
$lid = xtubeCleanRequestVars($_REQUEST, 'lid', 0);
$lid = intval($lid);

switch (strtolower($op)) {
    case 'updatenotice':
        $ack         = xtubeCleanRequestVars($_REQUEST, 'ack', 0);
        $con         = xtubeCleanRequestVars($_REQUEST, 'con', 1);
        $update_mess = '';

        if ($ack && !$con) {
            $acknowledged = ($ack == 0) ? 1 : 0;
            $sql          = 'UPDATE ' . $xoopsDB->prefix('xoopstube_broken') . ' SET acknowledged=' . $acknowledged;
            if ($acknowledged == 0) {
                $sql .= ', confirmed=0 ';
            }
            $sql .= ' WHERE lid=' . $lid;
            if (!$result = $xoopsDB->queryF($sql)) {
                XoopsErrorHandler_HandleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }
            $update_mess = _AM_XTUBE_BROKEN_NOWACK;
        } elseif (!$ack && !$con) {
            $acknowledged = ($ack == 0) ? 1 : 0;
            $sql          = 'UPDATE ' . $xoopsDB->prefix('xoopstube_broken') . ' SET acknowledged=' . $acknowledged;
            if ($acknowledged == 0) {
                $sql .= ', confirmed=0 ';
            }
            $sql .= ' WHERE lid=' . $lid;
            if (!$result = $xoopsDB->queryF($sql)) {
                XoopsErrorHandler_HandleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }

            $update_mess = _AM_XTUBE_BROKEN_NOWACK;
        }

        if ($con) {
            $confirmed = ($con == 0) ? 1 : 0;
            $sql       = 'UPDATE ' . $xoopsDB->prefix('xoopstube_broken') . ' SET confirmed=' . $confirmed;
            if ($confirmed == 1) {
                $sql .= ', acknowledged=' . $confirmed;
            }
            $sql .= ' WHERE lid=' . $lid;
            if (!$result = $xoopsDB->queryF($sql)) {
                XoopsErrorHandler_HandleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }
            $update_mess = _AM_XTUBE_BROKEN_NOWCON;
        } elseif (!$con) {
            $confirmed = ($con == 0) ? 1 : 0;
            $sql       = 'UPDATE ' . $xoopsDB->prefix('xoopstube_broken') . ' SET confirmed=' . $confirmed;
            if ($confirmed == 1) {
                $sql .= ', acknowledged=' . $confirmed;
            }
            $sql .= ' WHERE lid=' . $lid;
            if (!$result = $xoopsDB->queryF($sql)) {
                XoopsErrorHandler_HandleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                return false;
            }
            $update_mess = _AM_XTUBE_BROKEN_NOWCON;
        }
        redirect_header('brokenvideo.php?op=default', 1, $update_mess);
        break;

    case 'delbrokenvideos':
        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xoopstube_broken') . ' WHERE lid=' . $lid);
        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE lid=' . $lid);
        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xoopstube_votedata') . ' WHERE lid=' . $lid);
        redirect_header('brokenvideo.php?op=default', 1, _AM_XTUBE_BROKENFILEDELETED);
        exit();
        break;

    case 'ignorebrokenvideos':
        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xoopstube_broken') . ' WHERE lid=' . $lid);
        redirect_header('brokenvideo.php?op=default', 1, _AM_XTUBE_BROKEN_FILEIGNORED);
        break;

    default:
        $result            = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('xoopstube_broken') . ' ORDER BY reportid'
        );
        $totalbrokenvideos = $xoopsDB->getRowsNum($result);

        xoops_cp_header();
//        xtubeRenderAdminMenu( _AM_XTUBE_BROKEN_FILE );

        echo '
        <fieldset style="border: #E8E8E8 1px solid;">
            <legend style="display: inline; font-weight: bold; color: #0A3760;">' . _AM_XTUBE_BROKEN_REPORTINFO . '</legend>
            <div style="padding: 8px;">' . _AM_XTUBE_BROKEN_REPORTSNO . '&nbsp;<b>' . $totalbrokenvideos . '</b><div>
            <div style="padding-left: 8px;">
           <ul>
                    <li>' . $xtubeImageArray['ignore'] . '&nbsp;&nbsp;' . _AM_XTUBE_BROKEN_IGNOREDESC . '</li>
                    <li>' . $xtubeImageArray['editimg'] . '&nbsp;&nbsp;' . _AM_XTUBE_BROKEN_EDITDESC . '</li>
                    <li>' . $xtubeImageArray['deleteimg'] . '&nbsp;&nbsp;' . _AM_XTUBE_BROKEN_DELETEDESC . '</li>
           </ul>
            </div>
        </fieldset>';

        echo '<table width="100%" border="0" cellspacing="1" cellpadding="2" class="outer" style="font-size: smaller;">';
        echo '<tr style="text-align: center;">';
        echo '<th width="3%" style="text-align: center;">' . _AM_XTUBE_BROKEN_ID . '</th>';
        echo '<th width="35%" style="text-align: left;">' . _AM_XTUBE_BROKEN_TITLE . '</th>';
        echo '<th>' . _AM_XTUBE_BROKEN_REPORTER . '</th>';
        echo '<th>' . _AM_XTUBE_BROKEN_FILESUBMITTER . '</th>';
        echo '<th>' . _AM_XTUBE_BROKEN_DATESUBMITTED . '</th>';
        echo '<th>' . _AM_XTUBE_BROKEN_ACKNOWLEDGED . '</th>';
        echo '<th>' . _AM_XTUBE_BROKEN_DCONFIRMED . '</th>';
        echo '<th style="text-align: center;">' . _AM_XTUBE_BROKEN_ACTION . '</th>';
        echo '</tr>';

        if ($totalbrokenvideos == 0) {
            echo '<tr style="text-align: center;"><td style="text-align: center;" class="head" colspan="8">'
                 . _AM_XTUBE_BROKEN_NOFILEMATCH . '</td></tr>';
        } else {
            while (list($reportid, $lid, $sender, $ip, $date, $confirmed, $acknowledged) = $xoopsDB->fetchRow(
                $result
            )) {
                $result2 = $xoopsDB->query(
                    'SELECT cid, title, vidid, submitter FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE lid='
                    . $lid
                );
                list($cid, $videoshowname, $vidid, $submitter) = $xoopsDB->fetchRow($result2);
                $email      = '';
                $sendername = '';

                if ($sender != 0) {
                    $result3 = $xoopsDB->query(
                        'SELECT uname, email FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $sender
                    );
                    list($sendername, $email) = $xoopsDB->fetchRow($result3);
                }
                $result4 = $xoopsDB->query(
                    'SELECT uname, email FROM ' . $xoopsDB->prefix('users') . '  WHERE uid=' . $sender
                );
                list($ownername, $owneremail) = $xoopsDB->fetchRow($result4);

                if ($ownername == '') {
                    $ownername = '&nbsp;';
                }

                $ack_image = ($acknowledged) ? $xtubeImageArray['ack_yes'] : $xtubeImageArray['ack_no'];
                $con_image = ($confirmed) ? $xtubeImageArray['con_yes'] : $xtubeImageArray['con_no'];

                echo '<tr style="text-align: center;">';
                echo '<td class="head">' . $reportid . '</td>';
                echo '<td class="even" style="text-align: left;"><a href="' . XOOPS_URL . '/modules/'
                     . $xoopsModule->getVar('dirname') . '/singlevideo.php?cid=' . $cid . '&amp;lid=' . $lid
                     . '" target="_blank">' . $videoshowname . '</a></td>';

                if ($email == '') {
                    echo '<td class="even">' . XoopsUserUtility::getUnameFromId($sender) . ' (' . $ip . ')</td>';
                } else {
                    echo '<td class="even"><a href="mailto:' . $email . '">' . XoopsUserUtility::getUnameFromId($sender)
                         . '</a> (' . $ip . ')</td>';
                }
                if ($owneremail == '') {
                    echo '<td class="even">' . $ownername . '</td>';
                } else {
                    echo '<td class="even"><a href="mailto:' . $owneremail . '">' . $ownername . '</a></td>';
                }
                echo '<td class="even" style="text-align: center;">' . xtubeGetTimestamp(
                        formatTimestamp($date, $xoopsModuleConfig["dateformatadmin"])
                    ) . '</td>';
                echo '<td class="even"><a href="brokenvideo.php?op=updateNotice&amp;lid=' . $lid . '&ack=' . intval(
                        $acknowledged
                    ) . '">' . $ack_image . ' </a></td>';
                echo '<td class="even"><a href="brokenvideo.php?op=updateNotice&amp;lid=' . $lid . '&con=' . intval(
                        $confirmed
                    ) . '">' . $con_image . '</a></td>';
                echo '<td class="even" style="text-align: center;" nowrap>';
                echo '<a href="brokenvideo.php?op=ignorebrokenvideos&amp;lid=' . $lid . '">' . $xtubeImageArray['ignore']
                     . '</a>&nbsp;';
                echo '<a href="main.php?op=edit&amp;lid=' . $lid . '">' . $xtubeImageArray['editimg'] . '</a>&nbsp;';
                echo '<a href="brokenvideo.php?op=delbrokenvideos&amp;lid=' . $lid . '">' . $xtubeImageArray['deleteimg']
                     . '</a>';
                echo '</td></tr>';
            }
        }
        echo '</table>';
}
include 'admin_footer.php';
