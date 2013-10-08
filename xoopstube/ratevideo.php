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


include 'header.php';

global $xtubemyts, $xoTheme;

// Check if videoload POSTER is voting (UNLESS Anonymous users allowed to post)
$lid = xtubeCleanRequestVars($_REQUEST, 'lid', '');
$lid = intval($lid);

$ip         = getenv("REMOTE_ADDR");
$ratinguser = (!is_object($xoopsUser)) ? 0 : $xoopsUser->getVar('uid');

if ($xoopsModuleConfig['showrating'] == 0 || $lid == '') {
    $ratemessage = _MD_XTUBE_CANTVOTEOWN;
    redirect_header('index.php', 4, $ratemessage);
    exit();
}

if ($ratinguser != 0) {
    $result = $xoopsDB->query(
        'SELECT cid, submitter FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE lid=' . intval($lid)
    );
    while (list($cid, $ratinguserDB) = $xoopsDB->fetchRow($result)) {
        if ($ratinguserDB == $ratinguser) {
            $ratemessage = _MD_XTUBE_CANTVOTEOWN;
            redirect_header('singlevideo.php?cid=' . intval($cid) . '&amp;lid=' . intval($lid), 4, $ratemessage);
            exit();
        }
    }
    // Check if REG user is trying to vote twice.
    $result = $xoopsDB->query(
        'SELECT cid, ratinguser FROM ' . $xoopsDB->prefix('xoopstube_votedata') . ' WHERE lid=' . intval($lid)
    );
    while (list($cid, $ratinguserDB) = $xoopsDB->fetchRow($result)) {
        if ($ratinguserDB == $ratinguser) {
            $ratemessage = _MD_XTUBE_VOTEONCE;
            redirect_header('singlevideo.php?cid=' . intval($cid) . '&amp;lid=' . intval($lid), 4, $ratemessage);
            exit();
        }
    }
} else {
    // Check if ANONYMOUS user is trying to vote more than once per day.
    $yesterday = (time() - (86400 * $anonwaitdays));
    $result    = $xoopsDB->query(
        'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xoopstube_votedata') . ' WHERE lid=' . intval($lid)
        . ' AND ratinguser=0 AND ratinghostname=' . $ip . '  AND ratingtimestamp > ' . $yesterday
    );
    list($anonvotecount) = $xoopsDB->fetchRow($result);
    if ($anonvotecount >= 1) {
        $ratemessage = _MD_XTUBE_VOTEONCE;
        redirect_header('singlevideo.php?cid=' . intval($cid) . '&amp;lid=' . intval($lid), 4, $ratemessage);
        exit();
    }
}

if (!empty($_POST['submit'])) {
    $ratinguser = (!is_object($xoopsUser)) ? 0 : $xoopsUser->getVar('uid');
    // Make sure only 1 anonymous from an IP in a single day.
    $anonwaitdays = 1;
    $ip           = getenv('REMOTE_ADDR');
    $lid          = xtubeCleanRequestVars($_REQUEST, 'lid', 0);
    $cid          = xtubeCleanRequestVars($_REQUEST, 'cid', 0);
    $rating       = xtubeCleanRequestVars($_REQUEST, 'rating', 0);
    $title        = $xtubemyts->addslashes(trim($_POST['title']));
    $lid          = intval($lid);
    $cid          = intval($cid);
    $rating       = intval($rating);
    // Check if Rating is Null
    if ($rating == '--') {
        $ratemessage = _MD_XTUBE_NORATING;
        redirect_header('ratevideo.php?cid=' . intval($cid) . '&amp;lid=' . intval($lid), 4, $ratemessage);
        exit();
    }
    // All is well.  Add to Line Item Rate to DB.
    $newid    = $xoopsDB->genId($xoopsDB->prefix('xoopstube_votedata') . '_ratingid_seq');
    $datetime = time();
    $sql      = sprintf(
        "INSERT INTO %s (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp, title) VALUES (%u, %u, %u, %u, %s, %u, %s)",
        $xoopsDB->prefix('xoopstube_votedata'),
        $newid,
        intval($lid),
        $ratinguser,
        $rating,
        $xoopsDB->quoteString($ip),
        $datetime,
        $xoopsDB->quoteString($title)
    );
    if (!$result = $xoopsDB->query($sql)) {
        $ratemessage = _MD_XTUBE_ERROR;
    } else {
        // All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
        xtubeUpdateRating($lid);
        $ratemessage = _MD_XTUBE_VOTEAPPRE . '<br />' . sprintf(_MD_XTUBE_THANKYOU, $xoopsConfig['sitename']);
    }
    redirect_header('singlevideo.php?cid=' . intval($cid) . '&amp;lid=' . intval($lid), 4, $ratemessage);
    exit();
} else {
    $xoopsOption['template_main'] = 'xoopstube_ratevideo.html';
    include XOOPS_ROOT_PATH . '/header.php';

    $catarray['imageheader'] = xtubeRenderImageHeader();
    $cid                     = xtubeCleanRequestVars($_REQUEST, 'cid', 0);
    $cid                     = intval($cid);

    $catarray['imageheader'] = xtubeRenderImageHeader();
    $xoopsTpl->assign('catarray', $catarray);

    $result = $xoopsDB->query(
        'SELECT title FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE lid=' . intval($lid)
    );
    list($title) = $xoopsDB->fetchRow($result);
    $xoopsTpl->assign(
        'video',
        array(
             'id'    => intval($lid),
             'cid'   => intval($cid),
             'title' => $xtubemyts->htmlSpecialCharsStrip($title)
        )
    );

    xtubeSetNoIndexNoFollow();

    $xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));
    include XOOPS_ROOT_PATH . '/footer.php';
}

xtubeSetNoIndexNoFollow();

$xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));
include XOOPS_ROOT_PATH . '/footer.php';
