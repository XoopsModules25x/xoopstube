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


/**
 * @param int    $cid
 * @param string $permType
 * @param bool   $redirect
 * @return bool
 */
function checkBlockGroups($cid = 0, $permType = 'XTubeCatPerm', $redirect = false)
{
    $mydirname = basename(dirname(dirname(__FILE__)));
    global $xoopsUser;

    $groups         = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler  = & xoops_gethandler('groupperm');
    $module_handler = & xoops_gethandler('module');
    $module         = & $module_handler->getByDirname($mydirname);
    if (!$gperm_handler->checkRight($permType, $cid, $groups, $module->getVar('mid'))) {
        if ($redirect == false) {
            return false;
        } else {
            redirect_header('index.php', 3, _NOPERM);
            exit();
        }
    }
    unset($module);

    return true;
}

/**
 * @param int    $cid
 * @param string $permType
 * @param bool   $redirect
 * @return bool
 */
function xtubeCheckBlockGroups($cid = 0, $permType = 'XTubeCatPerm', $redirect = false)
{
    $mydirname = basename(dirname(dirname(__FILE__)));
    global $xoopsUser;

    $groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $modhandler    = xoops_gethandler('module');
    $xtubeModule   = $modhandler->getByDirname($mydirname);
    $gperm_handler = & xoops_gethandler('groupperm');
    if (!$gperm_handler->checkRight($permType, $cid, $groups, $xtubeModule->getVar('mid'))) {
        if ($redirect == false) {
            return false;
        }
    }

    return true;
}

/**
 * @param       $bvidid
 * @param       $btitle
 * @param       $bsource
 * @param       $bpicurl
 * @param array $size
 * @return string
 */
function getThumbsTopVideoBlock($bvidid, $btitle, $bsource, $bpicurl, $size = array())
{
    $thumbb            = '';
    $modhandler        = xoops_gethandler('module');
    $xtubeModule       = $modhandler->getByDirname('xoopstube');
    $config_handler    = xoops_gethandler('config');
    $xtubeModuleConfig = $config_handler->getConfigsByCat(0, $xtubeModule->getVar('mid'));
    if (isset($size['shotwidth'])) {
        $xtubeModuleConfig['shotwidth'] = $size['shotwidth'];
    }
    if (isset($size['shotheight'])) {
        $xtubeModuleConfig['shotheight'] = $size['shotheight'];
    }
// Determine if video source YouTube
    if ($bsource == 0) {
        $thumbb
            =
            '<img src="http://img.youtube.com/vi/' . $bvidid . '/default.jpg" title="' . $btitle . '" alt="' . $btitle
            . '" width="' . $xtubeModuleConfig['shotwidth'] . '" height="'
            . $xtubeModuleConfig['shotheight'] . '"  border="0" />';
    }
    // Determine if video source MetaCafe
    if ($bsource == 1) {
        list($metaclip) = explode('[/]', $bvidid);
        $videothumb['metathumb'] = $metaclip;
        $thumbb
                                 =
            '<img src="http://www.metacafe.com/thumb/' . $videothumb['metathumb'] . '.jpg" title="' . $btitle
            . '" alt="' . $btitle . '" width="' . $xtubeModuleConfig['shotwidth'] . '" height="'
            . $xtubeModuleConfig['shotheight'] . '"  border="0" />';
    }
// Determine if video source iFilm/Spike
    if ($bsource == 2) {
        $thumbb = '<img src="http://img2.ifilmpro.com/resize/image/stills/films/resize/istd/' . $bvidid . '.jpg?width='
                  . $xtubeModuleConfig['shotwidth'] . ' title="' . $btitle . '" alt="' . $btitle . '" border="0" />';
    }
// Determine if video source Photobucket
    if ($bsource == 3) {
        $thumbb
            =
            '<img src="http://i153.photobucket.com/albums/' . $bvidid . '.jpg" title="' . $btitle . '" alt="' . $btitle
            . '" width="' . $xtubeModuleConfig['shotwidth'] . '" height="'
            . $xtubeModuleConfig['shotheight'] . '"  border="0" />';
    }
// Determine if video source Google Video / MySpace TV / DailyMotion
    if ($bsource == 100) {
        $thumbb = '<img src="' . $bpicurl . '" title="' . $btitle . '" alt="' . $btitle . '" width="'
                  . $xtubeModuleConfig['shotwidth'] . '" height="'
                  . $xtubeModuleConfig['shotheight'] . '"  border="0" />';
    }
// Determine if video source MySpace TV
    if ($bsource == 101) {
        $thumbb = '<img src="' . $bpicurl . '" title="' . $btitle . '" alt="' . $btitle . '" width="'
                  . $xtubeModuleConfig['shotwidth'] . '" height="'
                  . $xtubeModuleConfig['shotheight'] . '"  border="0" />';
    }
// Determine if video source DailyMotion
    if ($bsource == 102) {
        $thumbb = '<img src="' . $bpicurl . '" title="' . $btitle . '" alt="' . $btitle . '" width="'
                  . $xtubeModuleConfig['shotwidth'] . '" height="'
                  . $xtubeModuleConfig['shotheight'] . '"  border="0" />';
    }

    return $thumbb;
}

/* Function: b_xoopstube_spotlight_show
 * Input   : $options[0] = date for the most recent videos
 * 			   hits for the most popular videos
 *           $block['content'] = The optional above content
 *           $options[1] = How many videos are displayes
 * Output  : Returns the most recent or most popular videos
 */
/**
 * @param $options
 * @return array
 */
function getSpotlightVideos($options)
{
    global $xoopsDB, $xoopsModuleConfig;
    include_once dirname(dirname(__FILE__)) . '/include/video.php';
    $block             = array();
    $modhandler        = xoops_gethandler('module');
    $xtubeModule       = $modhandler->getByDirname('xoopstube');
    $config_handler    = xoops_gethandler('config');
    $xtubeModuleConfig = $config_handler->getConfigsByCat(0, $xtubeModule->getVar('mid'));
    $xtubemyts         = & MyTextSanitizer :: getInstance();

    $options[1] = 4;
    $result     = $xoopsDB->query(
        "SELECT lid, cid, title, vidid, date, hits, vidsource, picurl FROM " . $xoopsDB->prefix('xoopstube_videos')
        . " WHERE published > 0 AND published <= " . time() . " AND (expired = 0 OR expired > " . time()
        . ") AND offline = 0 ORDER BY " . $options[0] . " DESC",
        $options[1],
        0
    );

    $i = 0;
    while ($myrow = $xoopsDB->fetchArray($result)) {
        if (false == checkBlockGroups($myrow['cid']) || $myrow['cid'] == 0) {
            continue;
        }
        if (xtubeCheckBlockGroups($myrow['cid']) == false) {
            exit;
        }
        $videoload = array();
        $title     = $xtubemyts->htmlSpecialChars($xtubemyts->stripSlashesGPC($myrow["title"]));
        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = substr($myrow['title'], 0, ($options[2] - 1)) . "...";
            }
        }
        $videoload['id']    = intval($myrow['lid']);
        $videoload['cid']   = intval($myrow['cid']);
        $videoload['title'] = $title;
        if ($options[0] == "date") {
            $videoload['date'] = formatTimestamp($myrow['date'], $xtubeModuleConfig['dateformat']);
        } elseif ($options[0] == "hits") {
            $videoload['hits'] = $myrow['hits'];
        }

        $size               = array();
        $rate               = 425 / 350;
        $size['shotwidth']  = '100';
        $size['shotheight'] = intval($size['shotwidth'] / $rate);

        if ($i == 0 && $myrow['vidsource'] == 0) {
            $videowidth  = 340;
            $videoheight = intval($videowidth / $rate);
            $showvideo
                                    =
                '<object width="' . $videowidth . '" height="' . $videoheight
                . '"><param name="movie" value="http://www.youtube.com/v/' . $myrow['vidid']
                . '"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/'
                . $myrow['vidid'] . '" type="application/x-shockwave-flash" wmode="transparent" width="' . $videowidth
                . '" height="' . $videoheight . '"></embed></object>';
            $videoload['showvideo'] = $showvideo;
        }

        $videoload['videothumb'] = getThumbsTopVideoBlock(
            $myrow['vidid'],
            $title,
            $myrow['vidsource'],
            $myrow['picurl'],
            $size
        );

        $videoload['dirname'] = $xtubeModule->getVar('dirname');
        $block['videos'][]    = $videoload;
        $i++;
    }
    unset($_block_check_array);

    return $block;
}

// Function: showTopVideoBlock
// Input   : $options[0] = date for the most recent videos
// 			   hits for the most popular videos
//           $block['content'] = The optional above content
//           $options[1] = How many videos are displayes
//           $options[2] = Length of title
//           $options[3] = Set date format
// Output  : Returns the most recent or most popular videos
/**
 * @param $options
 * @return array
 */
function showTopVideoBlock($options)
{
    global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
    $mydirname         = basename(dirname(dirname(__FILE__)));
    $block             = array();
    $modhandler        = xoops_gethandler('module');
    $xtubeModule       = $modhandler->getByDirname($mydirname);
    $config_handler    = xoops_gethandler('config');
    $xtubeModuleConfig = $config_handler->getConfigsByCat(0, $xtubeModule->getVar('mid'));
    $xtubemyts         = & MyTextSanitizer :: getInstance();

    if (isset($options[4]) && ($options[4] > 0)) {
        $result = $xoopsDB->query(
            'SELECT lid, cid, title, vidid, screenshot, published, hits, vidsource, picurl FROM ' . $xoopsDB->prefix(
                'xoopstube_videos'
            ) . ' WHERE published>0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    AND cid=' . $options[4] . '
                                    ORDER BY ' . $options[0] . '
                                    DESC',
            $options[1],
            0
        );
    } else {
        $result = $xoopsDB->query(
            'SELECT lid, cid, title, vidid, screenshot, published, hits, vidsource, picurl FROM ' . $xoopsDB->prefix(
                'xoopstube_videos'
            ) . ' WHERE published>0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    ORDER BY ' . $options[0] . '
                                    DESC',
            $options[1],
            0
        );
    }

    include_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/include/video.php';
    include_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/include/functions.php';

    while ($myrow = $xoopsDB->fetchArray($result)) {

        if (false == checkBlockGroups($myrow['cid']) || $myrow['cid'] == 0) {
            continue;
        }

        if (xtubeCheckBlockGroups($myrow['cid']) == false) {
            exit;
        }

        $videoload = array();
        $title     = $xtubemyts->htmlSpecialChars($xtubemyts->stripSlashesGPC($myrow['title']));
        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = substr($myrow['title'], 0, ($options[2] - 1)) . '...';
            }
        }
        $videoload['id']    = intval($myrow['lid']);
        $videoload['cid']   = intval($myrow['cid']);
        $videoload['title'] = $title;
        if ($options[0] == 'published') {
            $videoload['date'] = xtubeGetTimestamp(formatTimestamp($myrow['published'], $options[3]));
        } elseif ($options[0] == 'hits') {
            $videoload['hits'] = $myrow['hits'];
        }

        $videoload['videothumb'] = xtubeGetVideoThumb(
            $myrow['vidid'],
            $title,
            $myrow['vidsource'],
            $myrow['picurl'],
            $xtubeModuleConfig['videoimgdir'] . '/' . $myrow['screenshot'],
            $xtubeModuleConfig['shotwidth'],
            $xtubeModuleConfig['shotheight']
        );
        $videoload['dirname']    = $xtubeModule->getVar('dirname');
        $videoload['width']      = $xtubeModuleConfig['shotwidth'] + 2;
        $block['videos'][]       = $videoload;
    }
    unset($_block_check_array);

    return $block;
}

// Function: getRandomVideo
// Output  : Returns random video
/**
 * @param $options
 * @return array
 */
function getRandomVideo($options)
{
    global $xoopsDB, $xoopsModuleConfig, $xtubemyts;
    $mydirname         = basename(dirname(dirname(__FILE__)));
    $block             = array();
    $modhandler        = xoops_gethandler('module');
    $xtubeModule       = $modhandler->getByDirname($mydirname);
    $config_handler    = xoops_gethandler('config');
    $xtubeModuleConfig = $config_handler->getConfigsByCat(0, $xtubeModule->getVar('mid'));
    $xtubemyts         = & MyTextSanitizer :: getInstance();

    if (isset($options[4]) && ($options[4] > 0)) {
        $result2 = $xoopsDB->query(
            'SELECT lid, cid, title, vidid, screenshot, published, vidsource, picurl FROM ' . $xoopsDB->prefix(
                'xoopstube_videos'
            ) . ' WHERE published > 0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    AND cid=' . $options[4] . '
                                    ORDER BY RAND() LIMIT ' . $options[1]
        );
    } else {
        $result2 = $xoopsDB->query(
            'SELECT lid, cid, title, vidid, screenshot, published, vidsource, picurl FROM ' . $xoopsDB->prefix(
                'xoopstube_videos'
            ) . ' WHERE published > 0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    ORDER BY RAND() LIMIT ' . $options[1]
        );
    }

    include_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/include/video.php';
    include_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/include/functions.php';

    while ($myrow = $xoopsDB->fetchArray($result2)) {

        if (false == checkBlockGroups($myrow['cid']) || $myrow['cid'] == 0) {
            continue;
        }
        $videorandom = array();
        $title       = $xtubemyts->htmlSpecialChars($xtubemyts->stripSlashesGPC($myrow['title']));
        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = substr($myrow['title'], 0, ($options[2] - 1)) . '...';
            }
        }
        $videorandom['id']    = intval($myrow['lid']);
        $videorandom['cid']   = intval($myrow['cid']);
        $videorandom['title'] = $title;
        if (isset($options[3])) {
            $videorandom['date'] = xtubeGetTimestamp(formatTimestamp($myrow['published'], $options[3]));
        }
        $videorandom['videothumb'] = xtubeGetVideoThumb(
            $myrow['vidid'],
            $myrow['title'],
            $myrow['vidsource'],
            $myrow['picurl'],
            $xtubeModuleConfig['videoimgdir'] . '/' . $myrow['screenshot'],
            $xtubeModuleConfig['shotwidth'],
            $xtubeModuleConfig['shotheight']
        );
        $videorandom['dirname']    = $xtubeModule->getVar('dirname');
        $videorandomh['width']     = $xtubeModuleConfig['shotwidth'] + 2;
        $block['random'][]         = $videorandom;
    }
    unset($_block_check_array);

    return $block;
}

// Function: b_xoopstube_random_h
// Output  : Returns random video in horizontal block
/**
 * @param $options
 * @return array
 */
function getRandomVideoForHorizontalBlock($options)
{
    global $xoopsDB, $xoopsModuleConfig, $xtubemyts;
    $mydirname         = basename(dirname(dirname(__FILE__)));
    $block             = array();
    $modhandler        = xoops_gethandler('module');
    $xtubeModule       = $modhandler->getByDirname($mydirname);
    $config_handler    = xoops_gethandler('config');
    $xtubeModuleConfig = $config_handler->getConfigsByCat(0, $xtubeModule->getVar('mid'));
    $xtubemyts         = & MyTextSanitizer :: getInstance();

    if (isset($options[4]) && ($options[4] > 0)) {
        $result2 = $xoopsDB->query(
            'SELECT lid, cid, title, vidid, screenshot, published, vidsource, picurl FROM ' . $xoopsDB->prefix(
                'xoopstube_videos'
            ) . ' WHERE published > 0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    AND cid=' . $options[4] . '
                                    ORDER BY RAND() LIMIT ' . $options[1]
        );
    } else {
        $result2 = $xoopsDB->query(
            'SELECT lid, cid, title, vidid, screenshot, published, vidsource, picurl FROM ' . $xoopsDB->prefix(
                'xoopstube_videos'
            ) . ' WHERE published > 0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    ORDER BY RAND() LIMIT ' . $options[1]
        );
    }

    include_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/include/video.php';
    include_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/include/functions.php';

    while ($myrow = $xoopsDB->fetchArray($result2)) {

        if (false == checkBlockGroups($myrow['cid']) || $myrow['cid'] == 0) {
            continue;
        }
        $videorandomh            = array();
        $title                   = $xtubemyts->htmlSpecialChars($xtubemyts->stripSlashesGPC($myrow['title']));
        $videorandomh['balloon'] = $myrow['title'];
        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = substr($myrow['title'], 0, ($options[2] - 1)) . '...';
            }
        }
        $videorandomh['id']    = intval($myrow['lid']);
        $videorandomh['cid']   = intval($myrow['cid']);
        $videorandomh['title'] = $title;
        if (isset($options[3])) {
            $videorandomh['date'] = xtubeGetTimestamp(formatTimestamp($myrow['published'], $options[3]));
        }
        include_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/include/video.php';
        $videorandomh['videothumb'] = xtubeGetVideoThumb(
            $myrow['vidid'],
            $myrow['title'],
            $myrow['vidsource'],
            $myrow['picurl'],
            $xtubeModuleConfig['videoimgdir'] . '/' . $myrow['screenshot'],
            $xtubeModuleConfig['shotwidth'],
            $xtubeModuleConfig['shotheight']
        );
        $videorandomh['dirname']    = $xtubeModule->getVar('dirname');
        $videorandomh['width']      = $xtubeModuleConfig['shotwidth'] + 2;
        $block['random'][]          = $videorandomh;
    }
    unset($_block_check_array);

    return $block;
}

// editTopVideoBlock()
// @param $options
// @return
/**
 * @param $options
 * @return string
 */
function editTopVideoBlock($options)
{
    $form = '' . _MB_XTUBE_DISP . '&nbsp;';
    $form .= "<input type='hidden' name='options[]' value='";
    if ($options[0] == "published") {
        $form .= "published'";
    }
    if ($options[0] == "random") {
        $form .= "random'";
    }
    if ($options[0] == "randomh") {
        $form .= "randomh'";
    } else {
        $form .= "hits'";
    }
    $form .= " />";
    $form .= "<input type='text' name='options[]' value='" . $options[1] . "' />&nbsp;" . _MB_XTUBE_FILES . "";
    $form .= "&nbsp;<br />" . _MB_XTUBE_CHARS . "&nbsp;<input type='text' name='options[]' value='" . $options[2]
             . "' />&nbsp;" . _MB_XTUBE_LENGTH . "";
    $form .= "&nbsp;<br />" . _MB_XTUBE_DATEFORMAT . "&nbsp;<input type='text' name='options[]' value='" . $options[3]
             . "' />&nbsp;" . _MB_XTUBE_DATEFORMATMANUAL;

    global $xoopsDB;
    $cat_arr = array();
    include_once XOOPS_ROOT_PATH . '/modules/xoopstube/class/xoopstubetree.php';
    $xt      = new XoopstubeTree($xoopsDB->prefix('xoopstube_cat'), 'cid', 'pid');
    $cat_arr = $xt->getChildTreeArray(0, 'title');

    $form .= "<br />" . _MB_XTUBE_SELECTCAT . "<br /><select name=\"options[]\" multiple=\"multiple\" size=\"5\">";
    if (array_search(0, $options) === false) {
        $form .= "<option value=\"0\">" . _MB_XTUBE_ALLCAT . "</option>";
    } else {
        $form .= "<option value=\"0\" selected=\"selected\">" . _MB_XTUBE_ALLCAT . "</option>";
    }

    foreach ($cat_arr as $catlist) {
        if (array_search($catlist, $options) === false) {
            $form .= "<option value=\"" . $catlist['cid'] . "\">" . $catlist['title'] . "</option>";
        } else {
            $form
                .= "<option value=\"" . $catlist['cid'] . "\" selected=\"selected\">" . $catlist['title'] . "</option>";
        }
    }
    $form .= "</select>";

    return $form;
}
