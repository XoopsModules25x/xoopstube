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

// defined('XOOPS_ROOT_PATH') || die('XOOPS Root Path not defined');

include_once __DIR__ . '/common.php';

/**
 * xtubeGetHandler()
 *
 * @param         $name
 * @param boolean $optional
 *
 * @return bool
 */
function &xtubeGetHandler($name, $optional = false)
{
    global $handlers, $xoopsModule;

    $name = strtolower(trim($name));
    if (!isset($handlers[$name])) {
        if (file_exists(
            $hnd_file
                = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/class/class_' . $name . '.php'
        )
        ) {
            require_once $hnd_file;
        }
        $class = 'xtube' . ucfirst($name) . 'Handler';
        if (class_exists($class)) {
            $handlers[$name] = new $class($GLOBALS['xoopsDB']);
        }
    }
    if (!isset($handlers[$name]) && !$optional) {
        trigger_error(
            '<div>Class <span style="font-weight: bold;">' . $class . '</span> does not exist.</div><div>Handler Name: ' . $name,
            E_USER_ERROR
        ) . '</div>';
    }

    return isset($handlers[$name]) ? $handlers[$name] : false;
}

/**
 * @param int    $cid
 * @param string $permType
 * @param bool   $redirect
 *
 * @return bool
 */
function xtubeCheckGroups($cid = 0, $permType = 'XTubeCatPerm', $redirect = false)
{
    global $xoopsUser, $xoopsModule;

    $groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler = & xoops_gethandler('groupperm');
    if (!$gperm_handler->checkRight($permType, $cid, $groups, $xoopsModule->getVar('mid'))) {
        if ($redirect == false) {
            return false;
        } else {
            redirect_header('index.php', 3, _NOPERM);
            exit();
        }
    }

    return true;
}

/**
 * @param int $lid
 *
 * @return bool
 */
function xtubeGetVoteDetails($lid = 0)
{
    global $xoopsDB;

    $sql
        = 'SELECT
        COUNT(rating) AS rate,
        MIN(rating) AS min_rate,
        MAX(rating) AS max_rate,
        AVG(rating) AS avg_rate,
        COUNT(ratinguser) AS rating_user,
        MAX(ratinguser) AS max_user,
        MAX(title) AS max_title,
        MIN(title) AS min_title,
        sum(ratinguser = 0) AS null_ratinguser
            FROM ' . $xoopsDB->prefix('xoopstube_votedata');
    if ($lid > 0) {
        $sql .= ' WHERE lid=' . $lid;
    }
    if (!$result = $xoopsDB->query($sql)) {
        return false;
    }
    $ret = $xoopsDB->fetchArray($result);

    return $ret;
}

/**
 * @param int $sel_id
 *
 * @return array|bool
 */
function xtubeCalculateVoteData($sel_id = 0)
{
    global $xoopsDB;
    $ret                  = array();
    $ret['useravgrating'] = 0;

    $sql = 'SELECT rating FROM ' . $xoopsDB->prefix('xoopstube_votedata');
    if ($sel_id != 0) {
        $sql .= ' WHERE lid=' . $sel_id;
    }
    if (!$result = $xoopsDB->query($sql)) {
        return false;
    }
    $ret['uservotes'] = $xoopsDB->getRowsNum($result);
    while (list($rating) = $xoopsDB->fetchRow($result)) {
        $ret['useravgrating'] += intval($rating);
    }
    if ($ret['useravgrating'] > 0) {
        $ret['useravgrating'] = number_format(($ret['useravgrating'] / $ret['uservotes']), 2);
    }

    return $ret;
}

/**
 * @param      $array
 * @param null $name
 * @param null $def
 * @param bool $strict
 * @param int  $lengthcheck
 *
 * @return array|int|null|string
 */
function xtubeCleanRequestVars(&$array, $name = null, $def = null, $strict = false, $lengthcheck = 15)
{
    // Sanitise $_request for further use.  This method gives more control and security.
    // Method is more for functionality rather than beauty at the moment, will correct later.
    unset($array['usercookie']);
    unset($array['PHPSESSID']);

    if (is_array($array) && $name == null) {
        $globals = array();
        foreach (array_keys($array) as $k) {
            $value = strip_tags(trim($array[$k]));
            if (strlen($value >= $lengthcheck)) {
                return null;
            }
            if (ctype_digit($value)) {
                $value = intval($value);
            } else {
                if ($strict == true) {
                    $value = preg_replace('/\W/', '', trim($value));
                }
                $value = strtolower(strval($value));
            }
            $globals[$k] = $value;
        }

        return $globals;
    }
    if (!isset($array[$name]) || !array_key_exists($name, $array)) {
        return $def;
    } else {
        $value = strip_tags(trim($array[$name]));
    }
    if (ctype_digit($value)) {
        $value = intval($value);
    } else {
        if ($strict == true) {
            $value = preg_replace('/\W/', '', trim($value));
        }
        $value = strtolower(strval($value));
    }

    return $value;
}

/**
 * @param int $cid
 *
 * @return string
 */
function xtubeRenderToolbar($cid = 0)
{
    $toolbar = '[ ';
    if (true == xtubeCheckGroups($cid, 'XTubeSubPerm')) {
        $toolbar .= '<a href="submit.php?cid=' . $cid . '">' . _MD_XOOPSTUBE_SUBMITVIDEO . '</a> | ';
    }
    $toolbar
        .= '<a href="newlist.php?newvideoshowdays=7">' . _MD_XOOPSTUBE_LATESTLIST . '</a> | <a href="topten.php?list=hit">' . _MD_XOOPSTUBE_POPULARITY . '</a> | <a href="topten.php?list=rate">'
        . _MD_XOOPSTUBE_TOPRATED . '</a> ]';

    return $toolbar;
}

/**
 *
 */
function xtubeGetServerStatistics()
{
    global $xoopsModule;
    echo '<fieldset style="border: #E8E8E8 1px solid;">
          <legend style="display: inline; font-weight: bold; color: #0A3760;">' . _AM_XOOPSTUBE_VIDEO_IMAGEINFO . '</legend>
          <div style="padding: 8px;">
            <img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/icon/server.png" alt="" style="float: left; padding-right: 10px;" />
          <div>' . _AM_XOOPSTUBE_VIDEO_SPHPINI . '</div>';

    $safemode        = (ini_get('safe_mode')) ? _AM_XOOPSTUBE_VIDEO_ON . _AM_XOOPSTUBE_VIDEO_SAFEMODEPROBLEMS : _AM_XOOPSTUBE_VIDEO_OFF;
    $registerglobals = (ini_get('register_globals') == '') ? _AM_XOOPSTUBE_VIDEO_OFF : _AM_XOOPSTUBE_VIDEO_ON;
    $videos          = (ini_get('file_uploads')) ? _AM_XOOPSTUBE_VIDEO_ON : _AM_XOOPSTUBE_VIDEO_OFF;

    $gdlib = (function_exists('gd_info')) ? _AM_XOOPSTUBE_VIDEO_GDON : _AM_XOOPSTUBE_VIDEO_GDOFF;
    echo '<li>' . _AM_XOOPSTUBE_VIDEO_GDLIBSTATUS . $gdlib;
    if (function_exists('gd_info')) {
        if (true == $gdlib = gd_info()) {
            echo '<li>' . _AM_XOOPSTUBE_VIDEO_GDLIBVERSION . '<b>' . $gdlib['GD Version'] . '</b>';
        }
    }
    echo '<br /><br />';
    echo '<li>' . _AM_XOOPSTUBE_VIDEO_SAFEMODESTATUS . $safemode;
    echo '<li>' . _AM_XOOPSTUBE_VIDEO_REGISTERGLOBALS . $registerglobals;
    echo '<li>' . _AM_XOOPSTUBE_VIDEO_SERVERUPLOADSTATUS . $videos;
    echo '</div>';
    echo '</fieldset>';
}

// xtubeDisplayIcons()
//
// @param  $time
// @param integer $status
// @param integer $counter
// @return
/**
 * @param     $time
 * @param int $status
 * @param int $counter
 *
 * @return string
 */
function xtubeDisplayIcons($time, $status = 0, $counter = 0)
{
    global $xoopsModuleConfig, $xoopsModule;

    $new = '';
    $pop = '';

    $newdate = (time() - (86400 * intval($xoopsModuleConfig['daysnew'])));
    $popdate = (time() - (86400 * intval($xoopsModuleConfig['daysupdated'])));

    if ($xoopsModuleConfig['displayicons'] != 3) {
        if ($newdate < $time) {
            if (intval($status) > 1) {
                if ($xoopsModuleConfig['displayicons'] == 1) {
                    $new
                        = '&nbsp;<img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar(
                            'dirname'
                        ) . '/assets/images/icon/updated.gif" alt="" style="vertical-align: middle;" />';
                }
                if ($xoopsModuleConfig['displayicons'] == 2) {
                    $new = '<em>' . _MD_XOOPSTUBE_UPDATED . '</em>';
                }
            } else {
                if ($xoopsModuleConfig['displayicons'] == 1) {
                    $new
                        = '&nbsp;<img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar(
                            'dirname'
                        ) . '/assets/images/icon/new.gif" alt="" style="vertical-align: middle;" />';
                }
                if ($xoopsModuleConfig['displayicons'] == 2) {
                    $new = '<em>' . _MD_XOOPSTUBE_NEW . '</em>';
                }
            }
        }
        if ($popdate > $time) {
            if ($counter >= $xoopsModuleConfig['popular']) {
                if ($xoopsModuleConfig['displayicons'] == 1) {
                    $pop
                        = '&nbsp;<img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar(
                            'dirname'
                        ) . '/assets/images/icon/popular.png" alt="" style="vertical-align: middle;" />';
                }
                if ($xoopsModuleConfig['displayicons'] == 2) {
                    $pop = '<em>' . _MD_XOOPSTUBE_POPULAR . '!</em>';
                }
            }
        }
    }
    $icons = $new . ' ' . $pop;

    return $icons;
}

if (!function_exists('xtubeConvertOrderByIn')) {
    // Reusable Link Sorting Functions
    // xtubeConvertOrderByIn()
    // @param  $orderby
    // @return
    /**
     * @param $orderby
     *
     * @return string
     */
    function xtubeConvertOrderByIn($orderby)
    {
        switch (trim($orderby)) {
            case 'titleA':
                $orderby = 'title ASC';
                break;
            case 'dateA':
                $orderby = 'published ASC';
                break;
            case 'hitsA':
                $orderby = 'hits ASC';
                break;
            case 'ratingA':
                $orderby = 'rating ASC';
                break;
            case 'countryA':
                $orderby = 'country ASC';
                break;
            case 'titleD':
                $orderby = 'title DESC';
                break;
            case 'hitsD':
                $orderby = 'hits DESC';
                break;
            case 'ratingD':
                $orderby = 'rating DESC';
                break;
            case'dateD':
                $orderby = 'published DESC';
                break;
            case 'countryD':
                $orderby = 'country DESC';
                break;
        }

        return $orderby;
    }
}
if (!function_exists('xtubeConvertOrderByTrans')) {
    /**
     * @param $orderby
     *
     * @return string
     */
    function xtubeConvertOrderByTrans($orderby)
    {
        switch ($orderby) {
            case 'hits ASC':
                $orderByTrans = _MD_XOOPSTUBE_POPULARITYLTOM;
                break;
            case 'hits DESC':
                $orderByTrans = _MD_XOOPSTUBE_POPULARITYMTOL;
                break;
            case 'title ASC':
                $orderByTrans = _MD_XOOPSTUBE_TITLEATOZ;
                break;
            case 'title DESC':
                $orderByTrans = _MD_XOOPSTUBE_TITLEZTOA;
                break;
            case 'published ASC':
                $orderByTrans = _MD_XOOPSTUBE_DATEOLD;
                break;
            case 'published DESC':
                $orderByTrans = _MD_XOOPSTUBE_DATENEW;
                break;
            case 'rating ASC':
                $orderByTrans = _MD_XOOPSTUBE_RATINGLTOH;
                break;
            case 'rating DESC':
                $orderByTrans = _MD_XOOPSTUBE_RATINGHTOL;
                break;
            case'country ASC':
                $orderByTrans = _MD_XOOPSTUBE_COUNTRYLTOH;
                break;
            case 'country DESC':
                $orderByTrans = _MD_XOOPSTUBE_COUNTRYHTOL;
                break;
        }

        return $orderByTrans;
    }
}
if (!function_exists('xtubeConvertOrderByOut')) {
    /**
     * @param $orderby
     *
     * @return string
     */
    function xtubeConvertOrderByOut($orderby)
    {
        switch ($orderby) {
            case 'title ASC':
                $orderby = 'titleA';
                break;
            case 'published ASC':
                $orderby = 'dateA';
                break;
            case 'hits ASC':
                $orderby = 'hitsA';
                break;
            case 'rating ASC':
                $orderby = 'ratingA';
                break;
            case 'country ASC':
                $orderby = 'countryA';
                break;
            case 'title DESC':
                $orderby = 'titleD';
                break;
            case 'published DESC':
                $orderby = 'dateD';
                break;
            case 'hits DESC':
                $orderby = 'hitsD';
                break;
            case 'rating DESC':
                $orderby = 'ratingD';
                break;
            case 'country DESC':
                $orderby = 'countryD';
                break;
        }

        return $orderby;
    }
}

// updaterating()
// @param  $sel_id
// @return updates rating data in itemtable for a given item
/**
 * @param $sel_id
 */
function xtubeUpdateRating($sel_id)
{
    global $xoopsDB;
    $query       = 'SELECT rating FROM ' . $xoopsDB->prefix('xoopstube_votedata') . ' WHERE lid=' . $sel_id;
    $voteresult  = $xoopsDB->query($query);
    $votesDB     = $xoopsDB->getRowsNum($voteresult);
    $totalrating = 0;
    while (list($rating) = $xoopsDB->fetchRow($voteresult)) {
        $totalrating += $rating;
    }
    $finalrating = $totalrating / $votesDB;
    $finalrating = number_format($finalrating, 4);
    $sql         = sprintf(
        'UPDATE %s SET rating = %u, votes = %u WHERE lid = %u',
        $xoopsDB->prefix('xoopstube_videos'),
        $finalrating,
        $votesDB,
        $sel_id
    );
    $xoopsDB->query($sql);
}

// totalcategory()
// @param integer $pid
// @return
/**
 * @param int $pid
 *
 * @return int
 */
function xtubeGetTotalCategoryCount($pid = 0)
{
    global $xoopsDB;

    $sql = 'SELECT cid FROM ' . $xoopsDB->prefix('xoopstube_cat');
    if ($pid > 0) {
        $sql .= ' WHERE pid = 0';
    }
    $result     = $xoopsDB->query($sql);
    $catlisting = 0;
    while (list($cid) = $xoopsDB->fetchRow($result)) {
        if (xtubeCheckGroups($cid)) {
            ++$catlisting;
        }
    }

    return $catlisting;
}

// xtubeGetTotalItems()
// @param integer $sel_id
// @param integer $get_child
// @param integer $return_sql
// @return
/**
 * @param int $sel_id
 * @param int $get_child
 * @param int $return_sql
 *
 * @return string
 */
function xtubeGetTotalItems($sel_id = 0, $get_child = 0, $return_sql = 0)
{
    global $xoopsDB, $mytree, $_check_array;

    if ($sel_id > 0) {
        $sql = 'SELECT a.lid, a.cid, a.published FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' a LEFT JOIN ' . $xoopsDB->prefix('xoopstube_altcat') . ' b' . ' ON b.lid=a.lid'
            . ' WHERE a.published > 0 AND a.published <= ' . time() . ' AND (a.expired = 0 OR a.expired > ' . time() . ') AND offline = 0 ' . ' AND (b.cid=a.cid OR (a.cid=' . $sel_id . ' OR b.cid='
            . $sel_id . '))' . ' GROUP BY a.lid, a.cid, a.published';
    } else {
        $sql
            = 'SELECT lid, cid, published FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' WHERE offline = 0 AND published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time()
            . ')';
    }
    if ($return_sql == 1) {
        return $sql;
    }

    $count          = 0;
    $published_date = 0;

    $arr    = array();
    $result = $xoopsDB->query($sql);
    while (list($lid, $cid, $published) = $xoopsDB->fetchRow($result)) {
        if (true == xtubeCheckGroups()) {
            ++$count;
            $published_date = ($published > $published_date) ? $published : $published_date;
        }
    }

    $child_count = 0;
    if ($get_child == 1) {
        $arr  = $mytree->getAllChildId($sel_id);
        $size = count($arr);
        for ($i = 0; $i < count($arr); ++$i) {
            $query2 = 'SELECT a.lid, a.published, a.cid FROM ' . $xoopsDB->prefix('xoopstube_videos') . ' a LEFT JOIN ' . $xoopsDB->prefix('xoopstube_altcat') . ' b' . ' ON b.lid = a.lid'
                . ' WHERE a.published > 0 AND a.published <= ' . time() . ' AND (a.expired = 0 OR a.expired > ' . time() . ') AND offline = 0' . ' AND (b.cid=a.cid OR (a.cid=' . $arr[$i]
                . ' OR b.cid=' . $arr[$i] . ')) GROUP BY a.lid, a.published, a.cid';

            $result2 = $xoopsDB->query($query2);
            while (list($lid, $published) = $xoopsDB->fetchRow($result2)) {
                if ($published == 0) {
                    continue;
                }
                $published_date = ($published > $published_date) ? $published : $published_date;
                ++$child_count;
            }
        }
    }
    $info['count']     = $count + $child_count;
    $info['published'] = $published_date;

    return $info;
}

/**
 * @param string $indeximage
 * @param string $indexheading
 *
 * @return string
 */
function xtubeRenderImageHeader($indeximage = '', $indexheading = '')
{
    global $xoopsDB, $xoopsModuleConfig;
    if ($indeximage == '') {
        $result = $xoopsDB->query('SELECT indeximage, indexheading FROM ' . $xoopsDB->prefix('xoopstube_indexpage'));
        list($indeximage, $indexheading) = $xoopsDB->fetchrow($result);
    }

    $image = '';
    if (!empty($indeximage)) {
        $image = xtubeDisplayImage($indeximage, 'index.php', $xoopsModuleConfig['mainimagedir'], $indexheading);
    }

    return $image;
}

/**
 * @param string $image
 * @param string $path
 * @param string $imgsource
 * @param string $alttext
 *
 * @return string
 */
function xtubeDisplayImage($image = '', $path = '', $imgsource = '', $alttext = '')
{
    global $xoopsConfig, $xoopsUser, $xoopsModule;

    $showimage = '';
    // Check to see if link is given
    if ($path) {
        $showimage = '<a href="' . $path . '">';
    }
    // checks to see if the file is valid else displays default blank image
    if (!is_dir(XOOPS_ROOT_PATH . "/{$imgsource}/{$image}") && file_exists(XOOPS_ROOT_PATH . "/{$imgsource}/{$image}")
    ) {
        $showimage
            .= "<img src='" . XOOPS_URL . "/{$imgsource}/{$image}' border='0' title='" . $alttext . "' alt='" . $alttext . "' /></a>";
    } else {
        if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
            $showimage .= '<img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/brokenimg.png" alt="' . _MD_XOOPSTUBE_ISADMINNOTICE . '" /></a>';
        } else {
            $showimage
                .= '<img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/blank.png" alt="' . $alttext . '" /></a>';
        }
    }
    clearstatcache();

    return $showimage;
}

/**
 * @param $published
 *
 * @return mixed
 */
function xtubeIsNewImage($published)
{
    global $xoopsModule, $xoopsDB;

    $oneday    = (time() - (86400 * 1));
    $threedays = (time() - (86400 * 3));
    $week      = (time() - (86400 * 7));

    $path = 'modules/' . $xoopsModule->getVar('dirname') . '/assets/images/icon';

    if ($published > 0 && $published < $week) {
        $indicator['image']   = "$path/linkload4.png";
        $indicator['alttext'] = _MD_XOOPSTUBE_NEWLAST;
    } elseif ($published >= $week && $published < $threedays) {
        $indicator['image']   = "$path/linkload3.png";
        $indicator['alttext'] = _MD_XOOPSTUBE_NEWTHIS;
    } elseif ($published >= $threedays && $published < $oneday) {
        $indicator['image']   = "$path/linkload2.png";
        $indicator['alttext'] = _MD_XOOPSTUBE_THREE;
    } elseif ($published >= $oneday) {
        $indicator['image']   = "$path/linkload1.png";
        $indicator['alttext'] = _MD_XOOPSTUBE_TODAY;
    } else {
        $indicator['image']   = "$path/linkload.png";
        $indicator['alttext'] = _MD_XOOPSTUBE_NO_FILES;
    }

    return $indicator;
}

/**
 * @param $haystack
 * @param $needle
 *
 * @return string
 */
function xtubeFindStringChar($haystack, $needle)
{
    return substr($haystack, 0, strpos($haystack, $needle) + 1);
}

/**
 * @param string $header
 * @param string $menu
 * @param string $extra
 * @param int    $scount
 *
 * @return bool|null
 */
function xtubeRenderAdminMenu($header = '', $menu = '', $extra = '', $scount = 4)
{
    global $xoopsConfig, $xoopsModule, $xoopsModuleConfig;

    $_named_vidid = xoops_getenv('PHP_SELF');
    if ($_named_vidid) {
        $thispage = basename($_named_vidid);
    }

    $op = (isset($_GET['op'])) ? $op = '?op=' . $_GET['op'] : '';

    echo '<h4 style="color: #2F5376;">' . _AM_XOOPSTUBE_MODULE_NAME . '</h4>';
    echo '
        <div style="font-size: 10px; text-align: left; color: #2F5376; padding: 2px 6px; line-height: 18px;">
        <span style="margin: 1px; padding: 4px; border: #E8E8E8 1px solid;">
            <a href="../admin/index.php">' . _AM_XOOPSTUBE_BINDEX . '</a>
        </span>
        <span  style="margin: 1px; padding: 4px; border: #E8E8E8 1px solid;">
            <a href="../index.php">' . _AM_XOOPSTUBE_GOMODULE . '</a>
        </span>
        <span  style="margin: 1px; padding: 4px; border: #E8E8E8 1px solid;">
            <a href="../../system/admin.php?fct=preferences&op=showmod&mod=' . $xoopsModule->getVar('mid') . '">' . _AM_XOOPSTUBE_PREFS . '</a>
        </span>
        <span  style="margin: 1px; padding: 4px; border: #E8E8E8 1px solid;">
            <a href="../admin/permissions.php">' . _AM_XOOPSTUBE_BPERMISSIONS . '</a>
        </span>
        <span  style="margin: 1px; padding: 4px; border: #E8E8E8 1px solid;">
            <a href="../admin/myblocksadmin.php">' . _AM_XOOPSTUBE_BLOCKADMIN . '</a>
        </span>
        <span  style="margin: 1px; padding: 4px; border: #E8E8E8 1px solid;">
            <a href="../../system/admin.php?fct=modulesadmin&op=update&module=' . $xoopsModule->getVar('dirname') . '">' . _AM_XOOPSTUBE_BUPDATE . '</a>
        </span>
        <span  style="margin: 1px; padding: 4px; border: #E8E8E8 1px solid;">
            <a href="../admin/about.php">' . _AM_XOOPSTUBE_ABOUT . '</a>
        </span>
        </div><br />';

    if (empty($menu)) {
        // You can change this part to suit your own module. Defining this here will save you form having to do this each time.
        $menu = array(
            _AM_XOOPSTUBE_MVIDEOS   => 'main.php?op=edit',
            _AM_XOOPSTUBE_MCATEGORY => 'category.php',
            _AM_XOOPSTUBE_INDEXPAGE => 'indexpage.php',
            //            _AM_XOOPSTUBE_MXOOPSTUBE     => 'main.php?op=edit',
            _AM_XOOPSTUBE_MUPLOADS  => 'upload.php',
            _AM_XOOPSTUBE_VUPLOADS  => 'vupload.php',
            _AM_XOOPSTUBE_MVOTEDATA => 'votedata.php',
            _AM_XOOPSTUBE_MCOMMENTS => '../../system/admin.php?module=' . $xoopsModule->getVar('mid') . '&status=0&limit=100&fct=comments&selsubmit=Go'
        );
    }

    if (!is_array($menu)) {
        echo '<table width="100%" cellpadding="2" cellspacing="1" class="outer">';
        echo '<tr><td class="even" align="center"><b>' . _AM_XOOPSTUBE_NOMENUITEMS . '</b></td></tr></table><br />';

        return false;
    }

    $oddnum = array(
        1  => '1',
        3  => '3',
        5  => '5',
        7  => '7',
        9  => '9',
        11 => '11',
        13 => '13'
    );
    // number of rows per menu
    $menurows = count($menu) / $scount;
    // total amount of rows to complete menu
    $menurow = ceil($menurows) * $scount;
    // actual number of menuitems per row
    $rowcount = $menurow / ceil($menurows);
    $count    = 0;
    for ($i = count($menu); $i < $menurow; ++$i) {
        $tempArray = array(1 => null);
        $menu      = array_merge($menu, $tempArray);
        ++$count;
    }

    // Sets up the width of each menu cell
    $width = 100 / $scount;
    $width = ceil($width);

    $menucount = 0;
    $count     = 0;
    // Menu table output
    echo '<table width="100%" cellpadding="2" cellspacing="1" class="outer" border="1"><tr>';
    // Check to see if $menu is and array
    if (is_array($menu)) {
        $classcounts = 0;
        $classcol[0] = 'even';

        for ($i = 1; $i < $menurow; ++$i) {
            ++$classcounts;
            if ($classcounts >= $scount) {
                if ($classcol[$i - 1] == 'odd') {
                    $classcol[$i] = ($classcol[$i - 1] == 'odd' && in_array($classcounts, $oddnum)) ? 'even' : 'odd';
                } else {
                    $classcol[$i] = ($classcol[$i - 1] == 'even' && in_array($classcounts, $oddnum)) ? 'odd' : 'even';
                }
                $classcounts = 0;
            } else {
                $classcol[$i] = ($classcol[$i - 1] == 'even') ? 'odd' : 'even';
            }
        }
        unset($classcounts);

        foreach ($menu as $menutitle => $menuvideo) {
            if ($thispage . $op == $menuvideo) {
                $classcol[$count] = 'outer';
            }
            echo '<td class="' . $classcol[$count] . '" style="padding: 4px; text-align: center;" valign="middle" width="' . $width . '%">';
            if (is_string($menuvideo)) {
                echo '<a href="' . $menuvideo . '"><span style="font-size: small;">' . $menutitle . '</span></a></td>';
            } else {
                echo '&nbsp;</td>';
            }
            ++$menucount;
            ++$count;
            // Break menu cells to start a new row if $count > $scount
            if ($menucount >= $scount) {
                echo '</tr>';
                $menucount = 0;
            }
        }
        echo '</table><br />';
        unset($count);
        unset($menucount);
    }
    // ###### Output warn messages for security ######
    if (is_dir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/update/')) {
        xoops_error(
            sprintf(
                _AM_XOOPSTUBE_WARNINSTALL1,
                XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/update/'
            )
        );
        echo '<br />';
    }

    $_file = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/update.php';
    if (file_exists($_file)) {
        xoops_error(
            sprintf(
                _AM_XOOPSTUBE_WARNINSTALL2,
                XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/update.php'
            )
        );
        echo '<br />';
    }

    $path1 = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['mainimagedir'];
    if (!is_dir($path1)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path1));
        echo '<br />';
    }
    if (!is_writable($path1)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path1));
        echo '<br />';
    }

    $path1_t = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['mainimagedir'] . '/thumbs';
    if (!is_dir($path1_t)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path1_t));
        echo '<br />';
    }
    if (!is_writable($path1_t)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path1_t));
        echo '<br />';
    }

    $path2 = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['videoimgdir'];
    if (!is_dir($path2)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path2));
        echo '<br />';
    }
    if (!is_writable($path2)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path2));
        echo '<br />';
    }

//    $path2_t = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['videoimgdir'] . '/thumbs';
//    if ( !is_dir( $path2_t ) || !is_writable( $path2_t ) ) {
//        xoops_error( sprintf( _AM_XOOPSTUBE_WARNINSTALL3, $path2_t ) );
//        echo '<br />';
//    }

    $path3 = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['catimage'];
    if (!is_dir($path3)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path3));
        echo '<br />';
    }
    if (!is_writable($path3)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path3));
        echo '<br />';
    }

    $path3_t = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['catimage'] . '/thumbs';
    if (!is_dir($path3_t)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path3_t));
        echo '<br />';
    }
    if (!is_writable($path3_t)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path3_t));
        echo '<br />';
    }

    $path4 = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['videodir'];
    if (!is_dir($path4)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path4));
        echo '<br />';
    }
    if (!is_writable($path4)) {
        xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path4));
        echo '<br />';
    }

    echo '<h4 style="color: #2F5376;">' . $header . '</h4>';
    if ($extra) {
        echo '<div>' . $extra . '</div>';
    }

    return null;
}

/**
 * @param $selected
 * @param $dirarray
 * @param $namearray
 */
function xtubeGetDirSelectOption($selected, $dirarray, $namearray)
{
    echo "<select size='1' name='workd' onchange='location.href=\"upload.php?rootpath=\"+this.options[this.selectedIndex].value'>";
    echo "<option value=''>--------------------------------------</option>";
    foreach ($namearray as $namearray => $workd) {
        if ($workd === $selected) {
            $opt_selected = 'selected';
        } else {
            $opt_selected = '';
        }
        echo '<option value="' . htmlspecialchars($namearray, ENT_QUOTES) . '" $opt_selected>' . $workd . '</option>';
    }
    echo '</select>';
}

/**
 * @param $selected
 * @param $dirarray
 * @param $namearray
 */
function xtubeVGetDirSelectOption($selected, $dirarray, $namearray)
{
    echo "<select size='1' name='workd' onchange='location.href=\"vupload.php?rootpath=\"+this.options[this.selectedIndex].value'>";
    echo "<option value=''>--------------------------------------</option>";
    foreach ($namearray as $namearray => $workd) {
        if ($workd === $selected) {
            $opt_selected = 'selected';
        } else {
            $opt_selected = '';
        }
        echo '<option value="' . htmlspecialchars($namearray, ENT_QUOTES) . '" $opt_selected>' . $workd . '</option>';
    }
    echo '</select>';
}

/**
 * @param        $FILES
 * @param string $uploaddir
 * @param string $allowed_mimetypes
 * @param string $redirecturl
 * @param int    $redirect
 * @param int    $usertype
 *
 * @return array|null
 */
function xtubeUploadFiles(
    $FILES,
    $uploaddir = 'uploads',
    $allowed_mimetypes = '',
    $redirecturl = 'index.php',
//    $num = 0,
    $redirect = 0,
    $usertype = 1
) {
    global $FILES, $xoopsConfig, $xoopsModuleConfig, $xoopsModule;

    $down = array();
    include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/class/uploader.php';
    if (empty($allowed_mimetypes)) {
        $allowed_mimetypes = xtube_retmime($FILES['userfile']['name'], $usertype);
    }
    $upload_dir = XOOPS_ROOT_PATH . '/' . $uploaddir . '/';

    $maxfilesize   = $xoopsModuleConfig['maxfilesize'];
    $maxfilewidth  = $xoopsModuleConfig['maximgwidth'];
    $maxfileheight = $xoopsModuleConfig['maximgheight'];

    $uploader = new XoopsMediaUploader($upload_dir, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
    $uploader->noAdminSizeCheck(1);
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
        if (!$uploader->upload()) {
            $errors = $uploader->getErrors();
            redirect_header($redirecturl, 2, $errors);
        } else {
            if ($redirect) {
                redirect_header($redirecturl, 1, _AM_XOOPSTUBE_UPLOADFILE);
            } else {
                if (is_file($uploader->savedDestination)) {
                    $down['url']  = XOOPS_URL . '/' . $uploaddir . '/' . strtolower($uploader->savedFileName);
                    $down['size'] = filesize(
                        XOOPS_ROOT_PATH . '/' . $uploaddir . '/' . strtolower($uploader->savedFileName)
                    );
                }

                return $down;
            }
        }
    } else {
        $errors = $uploader->getErrors();
        redirect_header($redirecturl, 1, $errors);
    }

    return null;
}

/**
 * @param $heading
 */
function xtubeRenderCategoryListHeader($heading)
{
    echo '
        <h4 style="font-weight: bold; color: #0A3760;">' . $heading . '</h4>
        <table width="100%" cellspacing="1" class="outer" summary>
        <tr>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_ID . '</th>
            <th style=" font-size: smaller;"><b>' . _AM_XOOPSTUBE_FCATEGORY_TITLE . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_FCATEGORY_WEIGHT . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_FCATEGORY_CIMAGE . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_CATSPONSOR . '</th>
<!--			<th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_PUBLISH . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_EXPIRE . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_ONLINE . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_ACTION . '</th> -->
        </tr>
        ';
}

/**
 * @param $published
 */
function xtubeRenderCategoryListBody($published)
{
    global $xtubemyts, $xtubeImageArray, $xoopsModuleConfig;

    $lid = $published['lid'];
    $cid = $published['cid'];

    $title     = '<a href="../singlevideo.php?cid=' . $published['cid'] . '&amp;lid=' . $published['lid'] . '">' . $xtubemyts->htmlSpecialCharsStrip(trim($published['title'])) . '</a>';
    $maintitle = urlencode($xtubemyts->htmlSpecialChars(trim($published['title'])));
    $cattitle
                  = '<a href="../viewcat.php?cid=' . $published['cid'] . '">' . xtubeGetCategoryTitle($published['cid']) . '</a>';
    $submitter    = xtubeGetLinkedUserNameFromId($published['submitter']);
    $returnsource = xtubeReturnSource($published['vidsource']);
    $submitted    = xtubeGetTimestamp(formatTimestamp($published['date'], $xoopsModuleConfig['dateformatadmin']));
    $publish      = ($published['published'] > 0) ? xtubeGetTimestamp(
        formatTimestamp($published['published'], $xoopsModuleConfig['dateformatadmin'])
    ) : 'Not Published';
    $expires      = $published['expired'] ? xtubeGetTimestamp(
        formatTimestamp($published['expired'], $xoopsModuleConfig['dateformatadmin'])
    ) : _AM_XOOPSTUBE_MINDEX_NOTSET;

    if ((($published['expired'] && $published['expired'] > time()) OR $published['expired'] == 0)
        && ($published['published'] && $published['published'] < time())
        && $published['offline'] == 0
    ) {
        $published_status = $xtubeImageArray['online'];
    } elseif (($published['expired'] && $published['expired'] < time()) && $published['offline'] == 0) {
        $published_status = $xtubeImageArray['expired'];
    } else {
        $published_status = ($published['published'] == 0) ? '<a href="newvideos.php">' . $xtubeImageArray['offline'] . '</a>' : $xtubeImageArray['offline'];
    }

    if ($published['vidsource'] == 200) {
        $icon = '<a href="main.php?op=edit&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_EDIT . '">' . $xtubeImageArray['editimg'] . '</a>&nbsp;';
    } else {
        $icon = '<a href="main.php?op=edit&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_EDIT . '">' . $xtubeImageArray['editimg'] . '</a>&nbsp;';
    }
    $icon .= '<a href="main.php?op=delete&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_DELETE . '">' . $xtubeImageArray['deleteimg'] . '</a>&nbsp;';
    $icon .= '<a href="altcat.php?op=main&amp;cid=' . $cid . '&amp;lid=' . $lid . '&amp;title=' . $published['title'] . '" title="' . _AM_XOOPSTUBE_ALTCAT_CREATEF . '">' . $xtubeImageArray['altcat']
        . '</a>';

    echo '
        <tr style="text-align: center; font-size: smaller;">
        <td class="head">' . $lid . '</span></td>
        <td class="even" style="text-align: left;">' . $title . '</td>
        <td class="even">' . $returnsource . '</td>
        <td class="even">' . $cattitle . '</td>
        <td class="even">' . $submitter . '</td>
        <td class="even">' . $publish . '</td>
        <td class="even">' . $expires . '</td>
        <td class="even" style="width: 4%;">' . $published_status . '</td>
        <td class="even" style="text-align: center; width: 6%; white-space: nowrap;">' . $icon . '</td>
        </tr>';
    unset($published);
}

/**
 * @param        $pubrowamount
 * @param        $start
 * @param string $art
 * @param string $_this
 * @param        $align
 *
 * @return bool|null
 */
function xtubeSetPageNavigationCategoryList($pubrowamount, $start, $art = 'art', $_this = '', $align)
{
    global $xoopsModuleConfig;

    if (($pubrowamount < $xoopsModuleConfig['admin_perpage'])) {
        return false;
    }
    // Display Page Nav if published is > total display pages amount.
    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    $pagenav = new XoopsPageNav($pubrowamount, $xoopsModuleConfig['admin_perpage'], $start, 'st' . $art, $_this);
    echo '<div style="text-align: ' . $align . '; padding: 8px;">' . $pagenav->renderNav() . '</div>';

    return null;
}

/**
 *
 */
function xtubeRenderCategoryListFooter()
{
    echo '<tr style="text-align: center;">
            <td class="head" colspan="7">' . _AM_XOOPSTUBE_MINDEX_NOVIDEOSFOUND . '</td>
          </tr>';
}

/**
 * @param $heading
 */
function xtubeRenderVideoListHeader($heading)
{
    echo '
        <h4 style="font-weight: bold; color: #0A3760;">' . $heading . '</h4>
        <table width="100%" cellspacing="1" class="outer" summary>
        <tr>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_ID . '</th>
            <th style=" font-size: smaller;"><b>' . _AM_XOOPSTUBE_MINDEX_TITLE . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_VIDSOURCE2 . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_CATTITLE . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_POSTER . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_PUBLISH . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_EXPIRE . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_ONLINE . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_ACTION . '</th>
        </tr>
        ';
}

/**
 * @param $published
 */
function xtubeRenderVideoListBody($published)
{
    global $xtubemyts, $xtubeImageArray, $xoopsModuleConfig, $pathIcon16;

    $lid = $published['lid'];
    $cid = $published['cid'];

    $title     = '<a href="../singlevideo.php?cid=' . $published['cid'] . '&amp;lid=' . $published['lid'] . '">' . $xtubemyts->htmlSpecialCharsStrip(trim($published['title'])) . '</a>';
    $maintitle = urlencode($xtubemyts->htmlSpecialChars(trim($published['title'])));
    $cattitle
                  = '<a href="../viewcat.php?cid=' . $published['cid'] . '">' . xtubeGetCategoryTitle($published['cid']) . '</a>';
    $submitter    = xtubeGetLinkedUserNameFromId($published['submitter']);
    $returnsource = xtubeReturnSource($published['vidsource']);
    $submitted    = xtubeGetTimestamp(formatTimestamp($published['date'], $xoopsModuleConfig['dateformatadmin']));
    $publish      = ($published['published'] > 0) ? xtubeGetTimestamp(
        formatTimestamp($published['published'], $xoopsModuleConfig['dateformatadmin'])
    ) : 'Not Published';
    $expires      = $published['expired'] ? xtubeGetTimestamp(
        formatTimestamp($published['expired'], $xoopsModuleConfig['dateformatadmin'])
    ) : _AM_XOOPSTUBE_MINDEX_NOTSET;

    if ((($published['expired'] && $published['expired'] > time()) OR $published['expired'] == 0)
        && ($published['published'] && $published['published'] < time())
        && $published['offline'] == 0
    ) {
//        $published_status = $xtubeImageArray['online'];
        $published_status = '<a href="main.php?op=toggle&amp;lid=' . $lid . '&amp;offline=' . $published['offline'] . '"><img src="' . $pathIcon16 . '/1.png' . '" /></a>';

    } elseif (($published['expired'] && $published['expired'] < time()) && $published['offline'] == 0) {
        $published_status = $xtubeImageArray['expired'];
    } else {
        $published_status = ($published['published'] == 0) ? '<a href="newvideos.php">' . $xtubeImageArray['offline'] . '</a>'
            : '<a href="main.php?op=toggle&amp;lid=' . $lid . '&amp;offline=' . $published['offline'] . '"><img src="' . $pathIcon16 . '/0.png' . '" /></a>';
    }

    if ($published['vidsource'] == 200) {
        $icon = '<a href="main.php?op=edit&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_EDIT . '">' . $xtubeImageArray['editimg'] . '</a>&nbsp;';
    } else {
        $icon = '<a href="main.php?op=edit&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_EDIT . '">' . $xtubeImageArray['editimg'] . '</a>&nbsp;';
    }
    $icon .= '<a href="main.php?op=delete&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_DELETE . '">' . $xtubeImageArray['deleteimg'] . '</a>&nbsp;';
    $icon .= '<a href="altcat.php?op=main&amp;cid=' . $cid . '&amp;lid=' . $lid . '&amp;title=' . $published['title'] . '" title="' . _AM_XOOPSTUBE_ALTCAT_CREATEF . '">' . $xtubeImageArray['altcat']
        . '</a>';

    echo '
        <tr style="text-align: center; font-size: smaller;">
        <td class="head">' . $lid . '</span></td>
        <td class="even" style="text-align: left;">' . $title . '</td>
        <td class="even">' . $returnsource . '</td>
        <td class="even">' . $cattitle . '</td>
        <td class="even">' . $submitter . '</td>
        <td class="even">' . $publish . '</td>
        <td class="even">' . $expires . '</td>
        <td class="even" style="width: 4%;">' . $published_status . '</td>
        <td class="even" style="text-align: center; width: 6%; white-space: nowrap;">' . $icon . '</td>
        </tr>';
    unset($published);
}

/**
 * @param $catt
 *
 * @return mixed
 */
function xtubeGetCategoryTitle($catt)
{
    global $xoopsDB;
    $sql    = 'SELECT title FROM ' . $xoopsDB->prefix('xoopstube_cat') . ' WHERE cid=' . $catt;
    $result = $xoopsDB->query($sql);
    $result = $xoopsDB->fetchArray($result);

    return $result['title'];
}

/**
 *
 */
function xtubeRenderVideoListFooter()
{
    echo '<tr style="text-align: center;">
            <td class="head" colspan="7">' . _AM_XOOPSTUBE_MINDEX_NOVIDEOSFOUND . '</td>
          </tr>';
}

/**
 * @param        $pubrowamount
 * @param        $start
 * @param string $art
 * @param string $_this
 * @param        $align
 *
 * @return bool|null
 */
function xtubeSetPageNavigationVideoList($pubrowamount, $start, $art = 'art', $_this = '', $align)
{
    global $xoopsModuleConfig;

    if (($pubrowamount < $xoopsModuleConfig['admin_perpage'])) {
        return false;
    }
    // Display Page Nav if published is > total display pages amount.
    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    $pagenav = new XoopsPageNav($pubrowamount, $xoopsModuleConfig['admin_perpage'], $start, 'st' . $art, $_this);
    echo '<div style="text-align: ' . $align . '; padding: 8px;">' . $pagenav->renderNav() . '</div>';

    return null;
}

/**
 * @param $document
 *
 * @return mixed
 */
function xtubeConvertHtml2Text($document)
{
    // PHP Manual:: function preg_replace
    // $document should contain an HTML document.
    // This will remove HTML tags, javascript sections
    // and white space. It will also convert some
    // common HTML entities to their text equivalent.
    // Credits : newbb2
    $search = array(
        "'<script[^>]*?>.*?</script>'si", // Strip out javascript
        "'<img.*?/>'si", // Strip out img tags
        "'<[\/\!]*?[^<>]*?>'si", // Strip out HTML tags
        "'([\r\n])[\s]+'", // Strip out white space
        "'&(quot|#34);'i", // Replace HTML entities
        "'&(amp|#38);'i",
        "'&(lt|#60);'i",
        "'&(gt|#62);'i",
        "'&(nbsp|#160);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i",
    ); // evaluate as php

    $replace = array(
        "",
        "",
        "",
        "\\1",
        "\"",
        "&",
        "<",
        ">",
        " ",
        chr(161),
        chr(162),
        chr(163),
        chr(169),
    );

    $text = preg_replace($search, $replace, $document);

    return $text;
}

// Check if Tag module is installed
/**
 * @return bool
 */
function xtubeIsModuleTagInstalled()
{
    static $isModuleTagInstalled;
    if (!isset($isModuleTagInstalled)) {
        $modules_handler = xoops_gethandler('module');
        $tag_mod         = $modules_handler->getByDirName('tag');
        if (!$tag_mod) {
            $tag_mod = false;
        } else {
            $isModuleTagInstalled = $tag_mod->getVar('isactive') == 1;
        }
    }

    return $isModuleTagInstalled;
}

// Add item_tag to Tag-module
/**
 * @param $lid
 * @param $item_tag
 */
function xtubeUpdateTag($lid, $item_tag)
{
    global $xoopsModule;
    if (xtubeIsModuleTagInstalled()) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
        $tag_handler = xoops_getmodulehandler('tag', 'tag');
        $tag_handler->updateByItem($item_tag, $lid, $xoopsModule->getVar('dirname'), 0);
    }
}

/**
 * @param $lid
 */
function xtubeUpdateCounter($lid)
{
    global $xoopsDB;
    $sql    = 'UPDATE ' . $xoopsDB->prefix('xoopstube_videos') . ' SET hits=hits+1 WHERE lid=' . intval($lid);
    $result = $xoopsDB->queryF($sql);
}

/**
 * @param $banner_id
 *
 * @return null|string
 */
function xtubeGetBannerFromBannerId($banner_id)
{
###### Hack by www.stefanosilvestrini.com ######
    global $xoopsConfig;
    $db      =& XoopsDatabaseFactory::getDatabaseConnection();
    $bresult = $db->query('SELECT COUNT(*) FROM ' . $db->prefix('banner') . ' WHERE bid=' . $banner_id);
    list ($numrows) = $db->fetchRow($bresult);
    if ($numrows > 1) {
        $numrows = $numrows - 1;
        mt_srand((double)microtime() * 1000000);
        $bannum = mt_rand(0, $numrows);
    } else {
        $bannum = 0;
    }
    if ($numrows > 0) {
        $bresult = $db->query('SELECT * FROM ' . $db->prefix('banner') . ' WHERE bid=' . $banner_id, 1, $bannum);
        list ($bid, $cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date, $htmlbanner, $htmlcode) = $db->fetchRow(
            $bresult
        );
        if ($xoopsConfig['my_ip'] == xoops_getenv('REMOTE_ADDR')) {
            // EMPTY
        } else {
            $db->queryF(sprintf('UPDATE %s SET impmade = impmade+1 WHERE bid = %u', $db->prefix('banner'), $bid));
        }
        /* Check if this impression is the last one and print the banner */
        if ($imptotal == $impmade) {
            $newid = $db->genId($db->prefix('bannerfinish') . '_bid_seq');
            $sql   = sprintf(
                'INSERT INTO %s (bid, cid, impressions, clicks, datestart, dateend) VALUES (%u, %u, %u, %u, %u, %u)',
                $db->prefix('bannerfinish'),
                $newid,
                $cid,
                $impmade,
                $clicks,
                $date,
                time()
            );
            $db->queryF($sql);
            $db->queryF(sprintf('DELETE FROM %s WHERE bid = %u', $db->prefix('banner'), $bid));
        }
        if ($htmlbanner) {
            $bannerobject = $htmlcode;
        } else {
            $bannerobject = '<div align="center"><a href="' . XOOPS_URL . '/banners.php?op=click&bid=' . $bid . '" target="_blank">';
            if (stristr($imageurl, '.swf')) {
                $bannerobject = $bannerobject
                    . '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" width="468" height="60">'
                    . '<param name="movie" value="' . $imageurl . '"></param>' . '<param name="quality" value="high"></param>' . '<embed src="' . $imageurl
                    . '" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="468" height="60">'
                    . '</embed>' . '</object>';
            } else {
                $bannerobject = $bannerobject . '<img src="' . $imageurl . '" alt="" />';
            }
            $bannerobject = $bannerobject . '</a></div>';
        }

        return $bannerobject;
    }

    return null;
}

/**
 * @param $client_id
 *
 * @return null|string
 */
function xtubeGetBannerFromClientId($client_id)
{
###### Hack by www.stefanosilvestrini.com ######
    global $xoopsConfig;
    $db      =& XoopsDatabaseFactory::getDatabaseConnection();
    $bresult = $db->query('SELECT COUNT(*) FROM ' . $db->prefix('banner') . ' WHERE cid=' . $client_id);
    list ($numrows) = $db->fetchRow($bresult);
    if ($numrows > 1) {
        $numrows = $numrows - 1;
        mt_srand((double)microtime() * 1000000);
        $bannum = mt_rand(0, $numrows);
    } else {
        $bannum = 0;
    }
    if ($numrows > 0) {
        $bresult = $db->query(
            'SELECT * FROM ' . $db->prefix('banner') . ' WHERE cid=' . $client_id . ' ORDER BY rand()',
            1,
            $bannum
        );
        list ($bid, $cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date, $htmlbanner, $htmlcode) = $db->fetchRow(
            $bresult
        );
        if ($xoopsConfig['my_ip'] == xoops_getenv('REMOTE_ADDR')) {
            // EMPTY
        } else {
            $db->queryF(sprintf('UPDATE %s SET impmade = impmade+1 WHERE bid = %u', $db->prefix('banner'), $bid));
        }
        /* Check if this impression is the last one and print the banner */
        if ($imptotal == $impmade) {
            $newid = $db->genId($db->prefix('bannerfinish') . '_bid_seq');
            $sql   = sprintf(
                'INSERT INTO %s (bid, cid, impressions, clicks, datestart, dateend) VALUES (%u, %u, %u, %u, %u, %u)',
                $db->prefix('bannerfinish'),
                $newid,
                $cid,
                $impmade,
                $clicks,
                $date,
                time()
            );
            $db->queryF($sql);
            $db->queryF(sprintf('DELETE FROM %s WHERE bid = %u', $db->prefix('banner'), $bid));
        }
        if ($htmlbanner) {
            $bannerobject = $htmlcode;
        } else {
            $bannerobject = '<div align="center"><a href="' . XOOPS_URL . '/banners.php?op=click&bid=' . $bid . '" target="_blank">';
            if (stristr($imageurl, '.swf')) {
                $bannerobject = $bannerobject
                    . '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" width="468" height="60">'
                    . '<param name="movie" value="' . $imageurl . '"></param>' . '<param name="quality" value="high"></param>' . '<embed src="' . $imageurl
                    . '" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="468" height="60">'
                    . '</embed>' . '</object>';
            } else {
                $bannerobject = $bannerobject . '<img src="' . $imageurl . '" alt="" />';
            }
            $bannerobject = $bannerobject . '</a></div>';
        }

        return $bannerobject;
    }

    return null;
}

/**
 *
 */
function xtubeSetNoIndexNoFollow()
{
    global $xoTheme, $xoopsTpl;
    if (is_object($xoTheme)) {
        $xoTheme->addMeta('meta', 'robots', 'noindex,nofollow');
    } else {
        $xoopsTpl->assign('xoops_meta_robots', 'noindex,nofollow');
    }
}

/**
 * @param $userid
 *
 * @return string
 */
function xtubeGetLinkedUserNameFromId($userid)
{
    $userid = intval($userid);
    if ($userid > 0) {
        $member_handler =& xoops_gethandler('member');
        $user           =& $member_handler->getUser($userid);
        if (is_object($user)) {
            $linkeduser
                = '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $userid . '">' . $user->getVar('uname') . '</a>';

            return $linkeduser;
        }
    }

    return $GLOBALS['xoopsConfig']['anonymous'];
}

/**
 * @param $time
 *
 * @return string
 */
function xtubeGetTimestamp($time)
{
    global $xoopsConfig, $xoopsModuleConfig;
    $mydirname = basename(dirname(__DIR__));
    include_once XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/language/' . $xoopsConfig['language'] . '/local.php';

    $trans     = array(
        'Monday'    => _XOOPSTUBE_MONDAY,
        'Tuesday'   => _XOOPSTUBE_TUESDAY,
        'Wednesday' => _XOOPSTUBE_WEDNESDAY,
        'Thursday'  => _XOOPSTUBE_THURSDAY,
        'Friday'    => _XOOPSTUBE_FRIDAY,
        'Saturday'  => _XOOPSTUBE_SATURDAY,
        'Sunday'    => _XOOPSTUBE_SUNDAY,
        'Mon'       => _XOOPSTUBE_MON,
        'Tue'       => _XOOPSTUBE_TUE,
        'Wed'       => _XOOPSTUBE_WED,
        'Thu'       => _XOOPSTUBE_THU,
        'Fri'       => _XOOPSTUBE_FRI,
        'Sat'       => _XOOPSTUBE_SAT,
        'Sun'       => _XOOPSTUBE_SUN,
        'January'   => _XOOPSTUBE_JANUARI,
        'February'  => _XOOPSTUBE_FEBRUARI,
        'March'     => _XOOPSTUBE_MARCH,
        'April'     => _XOOPSTUBE_APRIL,
        'May'       => _XOOPSTUBE_MAY,
        'June'      => _XOOPSTUBE_JUNE,
        'July'      => _XOOPSTUBE_JULY,
        'August'    => _XOOPSTUBE_AUGUST,
        'September' => _XOOPSTUBE_SEPTEMBER,
        'October'   => _XOOPSTUBE_OCTOBER,
        'November'  => _XOOPSTUBE_NOVEMBER,
        'December'  => _XOOPSTUBE_DECEMBER,
        'Jan'       => _XOOPSTUBE_JAN,
        'Feb'       => _XOOPSTUBE_FEB,
        'Mar'       => _XOOPSTUBE_MAR,
        'Apr'       => _XOOPSTUBE_APR,
        //        'May'       => _XOOPSTUBE_MAY2,
        'Jun'       => _XOOPSTUBE_JUN,
        'Jul'       => _XOOPSTUBE_JUL,
        'Aug'       => _XOOPSTUBE_AUG,
        'Sep'       => _XOOPSTUBE_SEP,
        'Oct'       => _XOOPSTUBE_OCT,
        'Nov'       => _XOOPSTUBE_NOV,
        'Dec'       => _XOOPSTUBE_DEC
    );
    $timestamp = strtr($time, $trans);

    return $timestamp;
}

/**
 * Do some basic file checks and stuff.
 * Author: Andrew Mills  Email:  ajmills@sirium.net
 * from amReviews module
 */
function xtubeFileChecks()
{
    global $xoopsModule, $xoopsModuleConfig;

    echo "<fieldset>";
    echo "<legend style=\"color: #990000; font-weight: bold;\">" . _AM_XOOPSTUBE_FILECHECKS . "</legend>";

    $dirPhotos      = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['catimage'];
    $dirVideos      = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['videodir'];
    $dirScreenshots = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['videoimgdir'];

    if (file_exists($dirPhotos)) {
        if (!is_writable($dirPhotos)) {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . _AM_XOOPSTUBE_UNABLE_TO_WRITE . $dirPhotos . "<br />";
        } else {
            echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $dirPhotos . "<br />";
        }
    } else {
        echo "<span style=\" color: red; font-weight: bold;\">" . _AM_XOOPSTUBE_WARNING . "</span> " . $dirPhotos . " <span style=\" color: red; \">" . _AM_XOOPSTUBE_NOT_EXISTS . "</span> <br />";
    }
    // photothumbdir
    if (file_exists($dirVideos)) {
        if (!is_writable($dirVideos)) {
            echo "<span style=\" color: red; font-weight: bold;\">" . _AM_XOOPSTUBE_WARNING . "</span> " . _AM_XOOPSTUBE_UNABLE_TO_WRITE . $dirVideos . "<br />";
        } else {
            echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $dirVideos . "<br />";
        }
    } else {
        echo "<span style=\" color: red; font-weight: bold;\">" . _AM_XOOPSTUBE_WARNING . "</span> " . $dirVideos . " <span style=\" color: red; \">" . _AM_XOOPSTUBE_NOT_EXISTS . "</span> <br />";
    }
    // photohighdir
    if (file_exists($dirScreenshots)) {
        if (!is_writable($dirScreenshots)) {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . _AM_XOOPSTUBE_UNABLE_TO_WRITE . $dirScreenshots . "<br />";
        } else {
            echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $dirScreenshots . "<br />";
        }
    } else {
        echo "<span style=\" color: red; font-weight: bold;\">" . _AM_XOOPSTUBE_WARNING . "</span> " . $dirScreenshots . " <span style=\" color: red; \">" . _AM_XOOPSTUBE_NOT_EXISTS
            . "</span> <br />";
    }

    /**
     * Some info.
     */
    $uploads = (ini_get('file_uploads')) ? _AM_XOOPSTUBE_UPLOAD_ON : _AM_XOOPSTUBE_UPLOAD_OFF;
    echo "<br />";
    echo "<ul>";
    echo "<li>" . _AM_XOOPSTUBE_UPLOADMAX . "<b>" . ini_get('upload_max_filesize') . "</b></li>";
    echo "<li>" . _AM_XOOPSTUBE_POSTMAX . "<b>" . ini_get('post_max_size') . "</b></li>";
    echo "<li>" . _AM_XOOPSTUBE_UPLOADS . "<b>" . $uploads . "</b></li>";

    $gdinfo = gd_info();
    if (function_exists('gd_info')) {
        echo "<li>" . _AM_XOOPSTUBE_GDIMGSPPRT . "<b>" . _AM_XOOPSTUBE_GDIMGON . "</b></li>";
        echo "<li>" . _AM_XOOPSTUBE_GDIMGVRSN . "<b>" . $gdinfo['GD Version'] . "</b></li>";
    } else {
        echo "<li>" . _AM_XOOPSTUBE_GDIMGSPPRT . "<b>" . _AM_XOOPSTUBE_GDIMGOFF . "</b></li>";
    }
    echo "</ul>";

    //$inithingy = ini_get_all();
    //print_r($inithingy);

    echo "</fieldset>";

}

/**
 * @param      $path
 * @param int  $mode
 * @param      $fileSource
 * @param null $fileTarget
 */
function createDirectory($path, $mode = 0777, $fileSource, $fileTarget = null)
{
    if (!is_dir($path)) {
        mkdir($path, $mode);
        file_put_contents($path . '/index.html', '<script>history.go(-1);</script>');
        if (!empty($fileSource) && !empty($fileTarget)) {
            @copy($fileSource, $fileTarget);
        }
    }
    chmod($path, $mode);
}

/**
 * @return string
 */
function xtubeGetLetters()
{
    global $xoopsModule, $xoopsDB;

    $letterchoice          = '<div>' . _MD_XOOPSTUBE_BROWSETOTOPIC . '</div>';
    $alphabet              = getXtubeAlphabet();
    $num                   = count($alphabet) - 1;
    $counter               = 0;
    $distinctDbLetters_arr = array();
    $sql                   = 'SELECT DISTINCT (UPPER(LEFT(title, 1))) AS letter FROM ' . $xoopsDB->prefix(
            'xoopstube_videos WHERE expired = 0 AND offline = 0'
        );
    if ($result = $xoopsDB->query($sql)) {
        while ($row = $xoopsDB->fetchArray($result)) {
            $distinctDbLetters_arr[] = $row['letter'];
        }
    }
    unset($sql);

    while (list(, $ltr) = each($alphabet)) {

        if (in_array($ltr, $distinctDbLetters_arr)) {
            $letterchoice
                .= '<a class="xoopstube_letters xoopstube_letters_green" href="';
        } else {
            $letterchoice
                .= '<a class="xoopstube_letters" href="';
        }
        $letterchoice .= XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?list=' . $ltr . '">' . $ltr . '</a>';
        if ($counter == round($num / 2)) {
            $letterchoice .= '<br />';
        } elseif ($counter != $num) {
            $letterchoice .= '&nbsp;';
        }
        ++$counter;
    }

    return $letterchoice;
}

/**
 * @return mixed|string
 */
function xtubeLettersChoice()
{
    global $xoopsModule;

    $mydirname = $xoopsModule->getVar('dirname');
    include_once XOOPS_ROOT_PATH . "/modules/$mydirname/class/$mydirname.php";
    $xoopstube = XoopstubeXoopstube::getInstance();

    $a = $xoopstube->getHandler('xoopstube');
    $b = $a->getActiveCriteria();
    $mydirname = basename(dirname(__DIR__));

    $criteria = $xoopstube->getHandler('xoopstube')->getActiveCriteria();
    $criteria->setGroupby('UPPER(LEFT(title,1))');
    $countsByLetters = $xoopstube->getHandler($mydirname)->getCounts($criteria);
    // Fill alphabet array
    $alphabet       = getXtubeAlphabet();
    $alphabet_array = array();
    foreach ($alphabet as $letter) {
        $letter_array = array();
        if (isset($countsByLetters[$letter])) {
            $letter_array['letter'] = $letter;
            $letter_array['count']  = $countsByLetters[$letter];
            $letter_array['url']    = "" . XOOPS_URL . "/modules/$mydirname/viewcat.php?list={$letter}";
        } else {
            $letter_array['letter'] = $letter;
            $letter_array['count']  = 0;
            $letter_array['url']    = "";
        }
        $alphabet_array[$letter] = $letter_array;
        unset($letter_array);
    }
    // Render output
    if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
        include_once $GLOBALS['xoops']->path("/class/theme.php");
        $GLOBALS['xoTheme'] = new xos_opal_Theme();
    }
    require_once $GLOBALS['xoops']->path('class/template.php');
    $letterschoiceTpl          = new XoopsTpl();
    $letterschoiceTpl->caching = false; // Disable cache
    $letterschoiceTpl->assign('alphabet', $alphabet_array);
    $html = $letterschoiceTpl->fetch("db:" . $xoopstube->getModule()->dirname() . "_common_letterschoice.tpl");
    unset($letterschoiceTpl);

    return $html;
}

//===============  from WF-Downloads   ======================================

/**
 * @return bool
 */
function xtubeUserIsAdmin()
{
    global $xoopsUser;
    $xoopstube = XoopstubeXoopstube::getInstance();

    static $xtubeIsAdmin;

    if (isset($xtubeIsAdmin)) {
        return $xtubeIsAdmin;
    }

    if (!$xoopsUser) {
        $xtubeIsAdmin = false;
    } else {
        $xtubeIsAdmin = $xoopsUser->isAdmin($xoopstube->getModule()->getVar('mid'));
    }

    return $xtubeIsAdmin;
}
