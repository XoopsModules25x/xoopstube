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
use XoopsModules\Xoopstube\{Utility
};
$GLOBALS['xoopsOption']['template_main'] = 'xoopstube_ratevideo.tpl';
require_once __DIR__ . '/header.php';

global $myts, $xoTheme;

// Check if videoload POSTER is voting (UNLESS Anonymous users allowed to post)
$lid = Request::getInt('lid', Request::getInt('lid', '', 'POST'), 'GET');

$ip         = getenv('REMOTE_ADDR');
$ratinguser = (!is_object($GLOBALS['xoopsUser'])) ? 0 : $GLOBALS['xoopsUser']->getVar('uid');

if (0 == $GLOBALS['xoopsModuleConfig']['showrating'] || '' == $lid) {
    $ratemessage = _MD_XOOPSTUBE_CANTVOTEOWN;
    redirect_header('index.php', 4, $ratemessage);
}

if (0 !== $ratinguser) {
    $sql    = 'SELECT cid, submitter FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid;
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (list($cid, $ratinguserDB) = $GLOBALS['xoopsDB']->fetchRow($result)) {
        if ($ratinguserDB === $ratinguser) {
            $ratemessage = _MD_XOOPSTUBE_CANTVOTEOWN;
            redirect_header('singlevideo.php?cid=' . (int)$cid . '&amp;lid=' . $lid, 4, $ratemessage);
        }
    }
    // Check if REG user is trying to vote twice.
    $sql    = 'SELECT cid, ratinguser FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_votedata') . ' WHERE lid=' . $lid;
    $result = $GLOBALS['xoopsDB']->query($sql);
    if ($result) {
        while (list($cid, $ratinguserDB) = $GLOBALS['xoopsDB']->fetchRow($result)) {
            if ($ratinguserDB === $ratinguser) {
                $ratemessage = _MD_XOOPSTUBE_VOTEONCE;
                redirect_header('singlevideo.php?cid=' . (int)$cid . '&amp;lid=' . $lid, 4, $ratemessage);
            }
        }
    }

} else {
    // Check if ANONYMOUS user is trying to vote more than once per day.
    $yesterday = (time() - (86400 * $anonwaitdays));
    $sql       = 'SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_votedata') . ' WHERE lid=' . $lid . ' AND ratinguser=0 AND ratinghostname=' . $ip . '  AND ratingtimestamp > ' . $yesterday;
    $result    = $GLOBALS['xoopsDB']->query($sql);
    [$anonvotecount] = $GLOBALS['xoopsDB']->fetchRow($result);
    if ($anonvotecount >= 1) {
        $ratemessage = _MD_XOOPSTUBE_VOTEONCE;
        redirect_header('singlevideo.php?cid=' . (int)$cid . '&amp;lid=' . $lid, 4, $ratemessage);
    }
}

if (!empty(Request::getString('submit', ''))) {
    $ratinguser = (!is_object($GLOBALS['xoopsUser'])) ? 0 : $GLOBALS['xoopsUser']->getVar('uid');
    // Make sure only 1 anonymous from an IP in a single day.
    $anonwaitdays = 1;
    $ip           = getenv('REMOTE_ADDR');
    $lid          = Request::getInt('lid', 0, 'POST');
    $cid          = Request::getInt('cid', 0, 'POST');
    $rating       = Request::getInt('rating', 0, 'POST');
    //    $title        = $myts->addslashes(trim(Request::getString('title', '', 'POST')));
    $title = Request::getString('title', '', 'POST');
    // Check if Rating is Null
    if (0 == $rating) {
        $ratemessage = _MD_XOOPSTUBE_NORATING;
        redirect_header('ratevideo.php?cid=' . $cid . '&amp;lid=' . $lid, 4, $ratemessage);
    }
    // All is well.  Add to Line Item Rate to DB.
    $newid    = $GLOBALS['xoopsDB']->genId($GLOBALS['xoopsDB']->prefix('xoopstube_votedata') . '_ratingid_seq');
    $datetime = time();
    $sql      = sprintf(
        'INSERT INTO `%s` (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp, title) VALUES (%u, %u, %u, %u, %s, %u, %s)',
        $GLOBALS['xoopsDB']->prefix('xoopstube_votedata'),
        $newid,
        $lid,
        $ratinguser,
        $rating,
        $GLOBALS['xoopsDB']->quoteString($ip),
        $datetime,
        $GLOBALS['xoopsDB']->quoteString($title)
    );
    if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
        $ratemessage = _MD_XOOPSTUBE_ERROR;
    } else {
        // All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
        Utility::updateRating($lid);
        $ratemessage = _MD_XOOPSTUBE_VOTEAPPRE . '<br>' . sprintf(_MD_XOOPSTUBE_THANKYOU, $GLOBALS['xoopsConfig']['sitename']);
    }
    redirect_header('singlevideo.php?cid=' . $cid . '&amp;lid=' . $lid, 4, $ratemessage);
} else {
    //TODO add
    require_once XOOPS_ROOT_PATH . '/header.php';

    $catarray['imageheader'] = Utility::renderImageHeader();
    $cid                     = Request::getInt('cid', Request::getInt('cid', '', 'POST'), 'GET');

    $catarray['imageheader'] = Utility::renderImageHeader();
    $xoopsTpl->assign('catarray', $catarray);
    $xoopsTpl->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

    $result = $GLOBALS['xoopsDB']->query('SELECT title FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE lid=' . $lid);
    [$title] = $GLOBALS['xoopsDB']->fetchRow($result);
    $xoopsTpl->assign(
        'video',
        [
            'id'    => $lid,
            'cid'   => $cid,
            'title' => htmlspecialchars($title),
        ]
    );

    Utility::setNoIndexNoFollow();

    $xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));
    require_once XOOPS_ROOT_PATH . '/footer.php';
}

Utility::setNoIndexNoFollow();

$xoopsTpl->assign('module_dir', $xoopsModule->getVar('dirname'));
require_once XOOPS_ROOT_PATH . '/footer.php';
