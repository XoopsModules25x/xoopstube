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

/**
 * @param int    $cid
 * @param string $permType
 * @param bool   $redirect
 *
 * @return bool
 */
function checkBlockGroups($cid = 0, $permType = 'XTubeCatPerm', $redirect = false)
{
    $moduleDirName = basename(dirname(__DIR__));
    $groups        = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gpermHandler  = xoops_getHandler('groupperm');
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);
    if (!$gpermHandler->checkRight($permType, $cid, $groups, $module->getVar('mid'))) {
        if (false === $redirect) {
            return false;
        } else {
            redirect_header('index.php', 3, _NOPERM);
        }
    }
    unset($module);

    return true;
}

/**
 * @param int    $cid
 * @param string $permType
 * @param bool   $redirect
 *
 * @return bool
 */
function xtubeCheckBlockGroups($cid = 0, $permType = 'XTubeCatPerm', $redirect = false)
{
    $moduleDirName = basename(dirname(__DIR__));
    $groups        = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $xtubeModule   = $moduleHandler->getByDirname($moduleDirName);
    $gpermHandler  = xoops_getHandler('groupperm');
    if (!$gpermHandler->checkRight($permType, $cid, $groups, $xtubeModule->getVar('mid'))) {
        if (false === $redirect) {
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
 *
 * @return string
 */
function getThumbsTopVideoBlock($bvidid, $btitle, $bsource, $bpicurl, array $size = [])
{
    $thumbb = '';
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler     = xoops_getHandler('module');
    $xtubeModule       = $moduleHandler->getByDirname('xoopstube');
    $configHandler     = xoops_getHandler('config');
    $xtubeModuleConfig = $configHandler->getConfigsByCat(0, $xtubeModule->getVar('mid'));
    if (isset($size['shotwidth'])) {
        $xtubeModuleConfig['shotwidth'] = $size['shotwidth'];
    }
    if (isset($size['shotheight'])) {
        $xtubeModuleConfig['shotheight'] = $size['shotheight'];
    }
    // Determine if video source YouTube
    if (0 == $bsource) {
        $thumbb = '<img src="http://img.youtube.com/vi/' . $bvidid . '/default.jpg" title="' . $btitle . '" alt="' . $btitle . '" width="' . $xtubeModuleConfig['shotwidth'] . '" height="' . $xtubeModuleConfig['shotheight'] . '"  border="0">';
    }
    // Determine if video source MetaCafe
    if (1 == $bsource) {
        list($metaclip) = explode('[/]', $bvidid);
        $videothumb['metathumb'] = $metaclip;
        $thumbb                  = '<img src="http://www.metacafe.com/thumb/' . $videothumb['metathumb'] . '.jpg" title="' . $btitle . '" alt="' . $btitle . '" width="' . $xtubeModuleConfig['shotwidth'] . '" height="' . $xtubeModuleConfig['shotheight'] . '"  border="0">';
    }
    // Determine if video source iFilm/Spike
    if (2 == $bsource) {
        $thumbb = '<img src="http://img2.ifilmpro.com/resize/image/stills/films/resize/istd/' . $bvidid . '.jpg?width=' . $xtubeModuleConfig['shotwidth'] . ' title="' . $btitle . '" alt="' . $btitle . '" border="0">';
    }
    // Determine if video source Photobucket
    if (3 == $bsource) {
        $thumbb = '<img src="http://i153.photobucket.com/albums/' . $bvidid . '.jpg" title="' . $btitle . '" alt="' . $btitle . '" width="' . $xtubeModuleConfig['shotwidth'] . '" height="' . $xtubeModuleConfig['shotheight'] . '"  border="0">';
    }
    // Determine if video source Google Video / MySpace TV / DailyMotion
    if (100 == $bsource) {
        $thumbb = '<img src="' . $bpicurl . '" title="' . $btitle . '" alt="' . $btitle . '" width="' . $xtubeModuleConfig['shotwidth'] . '" height="' . $xtubeModuleConfig['shotheight'] . '"  border="0">';
    }
    // Determine if video source MySpace TV
    if (101 == $bsource) {
        $thumbb = '<img src="' . $bpicurl . '" title="' . $btitle . '" alt="' . $btitle . '" width="' . $xtubeModuleConfig['shotwidth'] . '" height="' . $xtubeModuleConfig['shotheight'] . '"  border="0">';
    }
    // Determine if video source DailyMotion
    if (102 == $bsource) {
        $thumbb = '<img src="' . $bpicurl . '" title="' . $btitle . '" alt="' . $btitle . '" width="' . $xtubeModuleConfig['shotwidth'] . '" height="' . $xtubeModuleConfig['shotheight'] . '"  border="0">';
    }

    return $thumbb;
}

/* Function: b_xoopstube_spotlight_show
 * Input   : $options[0] = date for the most recent videos
 *             hits for the most popular videos
 *           $block['content'] = The optional above content
 *           $options[1] = How many videos are displayes
 * Output  : Returns the most recent or most popular videos
 */
/**
 * @param $options
 *
 * @return array
 */
function getSpotlightVideos($options)
{
    require_once __DIR__ . '/../include/video.php';
    $block = [];
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler     = xoops_getHandler('module');
    $xtubeModule       = $moduleHandler->getByDirname('xoopstube');
    $configHandler     = xoops_getHandler('config');
    $xtubeModuleConfig = $configHandler->getConfigsByCat(0, $xtubeModule->getVar('mid'));
    $xtubemyts         = \MyTextSanitizer:: getInstance();

    $options[1] = 4;
    $result     = $GLOBALS['xoopsDB']->query('SELECT lid, cid, title, vidid, date, hits, vidsource, picurl FROM '
                                             . $GLOBALS['xoopsDB']->prefix('xoopstube_videos')
                                             . ' WHERE published > 0 AND published <= '
                                             . time()
                                             . ' AND (expired = 0 OR expired > '
                                             . time()
                                             . ') AND offline = 0 ORDER BY '
                                             . $options[0]
                                             . ' DESC', $options[1], 0);

    $i = 0;
    while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
        if (false === checkBlockGroups($myrow['cid']) || 0 == $myrow['cid']) {
            continue;
        }
        if (false === xtubeCheckBlockGroups($myrow['cid'])) {
            exit;
        }
        $videoload = [];
        $title     = $xtubemyts->htmlSpecialChars($xtubemyts->stripSlashesGPC($myrow['title']));
        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = substr($myrow['title'], 0, $options[2] - 1) . '...';
            }
        }
        $videoload['id']    = (int)$myrow['lid'];
        $videoload['cid']   = (int)$myrow['cid'];
        $videoload['title'] = $title;
        if ('date' === $options[0]) {
            $videoload['date'] = formatTimestamp($myrow['date'], $xtubeModuleConfig['dateformat']);
        } elseif ('hits' === $options[0]) {
            $videoload['hits'] = $myrow['hits'];
        }

        $size               = [];
        $rate               = 425 / 350;
        $size['shotwidth']  = '100';
        $size['shotheight'] = (int)($size['shotwidth'] / $rate);

        if (0 == $i && 0 == $myrow['vidsource']) {
            $videowidth             = 340;
            $videoheight            = (int)($videowidth / $rate);
            $showvideo              = '<object width="'
                                      . $videowidth
                                      . '" height="'
                                      . $videoheight
                                      . '"><param name="movie" value="http://www.youtube.com/v/'
                                      . $myrow['vidid']
                                      . '"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/'
                                      . $myrow['vidid']
                                      . '" type="application/x-shockwave-flash" wmode="transparent" width="'
                                      . $videowidth
                                      . '" height="'
                                      . $videoheight
                                      . '"></embed></object>';
            $videoload['showvideo'] = $showvideo;
        }

        $videoload['videothumb'] = getThumbsTopVideoBlock($myrow['vidid'], $title, $myrow['vidsource'], $myrow['picurl'], $size);

        $videoload['dirname'] = $xtubeModule->getVar('dirname');
        $block['videos'][]    = $videoload;
        ++$i;
    }
    unset($_block_check_array);

    return $block;
}

// Function: showTopVideoBlock
// Input   : $options[0] = date for the most recent videos
//             hits for the most popular videos
//           $block['content'] = The optional above content
//           $options[1] = How many videos are displayes
//           $options[2] = Length of title
//           $options[3] = Set date format
// Output  : Returns the most recent or most popular videos
/**
 * @param $options
 *
 * @return array
 */
function showTopVideoBlock($options)
{
    $moduleDirName = basename(dirname(__DIR__));
    $block         = [];
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler     = xoops_getHandler('module');
    $xtubeModule       = $moduleHandler->getByDirname($moduleDirName);
    $configHandler     = xoops_getHandler('config');
    $xtubeModuleConfig = $configHandler->getConfigsByCat(0, $xtubeModule->getVar('mid'));
    $xtubemyts         = \MyTextSanitizer:: getInstance();

    if (isset($options[4]) && ($options[4] > 0)) {
        $result = $GLOBALS['xoopsDB']->query('SELECT lid, cid, title, vidid, screenshot, published, hits, vidsource, picurl FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published>0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    AND cid=' . $options[4] . '
                                    ORDER BY ' . $options[0] . '
                                    DESC', $options[1], 0);
    } else {
        $result = $GLOBALS['xoopsDB']->query('SELECT lid, cid, title, vidid, screenshot, published, hits, vidsource, picurl FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published>0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    ORDER BY ' . $options[0] . '
                                    DESC', $options[1], 0);
    }

    require_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/include/video.php';
//    require_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/class/Utility.php';

    while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
        if (false === checkBlockGroups($myrow['cid']) || 0 == $myrow['cid']) {
            continue;
        }

        if (false === xtubeCheckBlockGroups($myrow['cid'])) {
            exit;
        }

        $videoload = [];
        $title     = $xtubemyts->htmlSpecialChars($xtubemyts->stripSlashesGPC($myrow['title']));
        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = substr($myrow['title'], 0, $options[2] - 1) . '...';
            }
        }
        $videoload['id']    = (int)$myrow['lid'];
        $videoload['cid']   = (int)$myrow['cid'];
        $videoload['title'] = $title;
        if ('published' === $options[0]) {
            $videoload['date'] = Xoopstube\Utility::getTimestamp(formatTimestamp($myrow['published'], $options[3]));
        } elseif ('hits' === $options[0]) {
            $videoload['hits'] = $myrow['hits'];
        }

        $videoload['videothumb'] = xtubeGetVideoThumb($myrow['vidid'], $title, $myrow['vidsource'], $myrow['picurl'], $xtubeModuleConfig['videoimgdir'] . '/' . $myrow['screenshot'], $xtubeModuleConfig['shotwidth'], $xtubeModuleConfig['shotheight']);
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
 *
 * @return array
 */
function getRandomVideo($options)
{
    global $xtubemyts;
    $utility = new Xoopstube\Utility();
    $moduleDirName = basename(dirname(__DIR__));
    $block         = [];
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler     = xoops_getHandler('module');
    $xtubeModule       = $moduleHandler->getByDirname($moduleDirName);
    $configHandler     = xoops_getHandler('config');
    $xtubeModuleConfig = $configHandler->getConfigsByCat(0, $xtubeModule->getVar('mid'));
    $xtubemyts         = \MyTextSanitizer:: getInstance();

    if (isset($options[4]) && ($options[4] > 0)) {
        $result2 = $GLOBALS['xoopsDB']->query('SELECT lid, cid, title, vidid, screenshot, published, vidsource, picurl FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published > 0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    AND cid=' . $options[4] . '
                                    ORDER BY RAND() LIMIT ' . $options[1]);
    } else {
        $result2 = $GLOBALS['xoopsDB']->query('SELECT lid, cid, title, vidid, screenshot, published, vidsource, picurl FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published > 0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    ORDER BY RAND() LIMIT ' . $options[1]);
    }

    require_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/include/video.php';
//    require_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/class/Utility.php';

    while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result2))) {
        if (false === checkBlockGroups($myrow['cid']) || 0 == $myrow['cid']) {
            continue;
        }
        $videorandom = [];
        $title       = $xtubemyts->htmlSpecialChars($xtubemyts->stripSlashesGPC($myrow['title']));
        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = substr($myrow['title'], 0, $options[2] - 1) . '...';
            }
        }
        $videorandom['id']    = (int)$myrow['lid'];
        $videorandom['cid']   = (int)$myrow['cid'];
        $videorandom['title'] = $title;
        if (isset($options[3])) {
            $videorandom['date'] = $utility::getTimestamp(formatTimestamp($myrow['published'], $options[3]));
        }
        $videorandom['videothumb'] = xtubeGetVideoThumb($myrow['vidid'], $myrow['title'], $myrow['vidsource'], $myrow['picurl'], $xtubeModuleConfig['videoimgdir'] . '/' . $myrow['screenshot'], $xtubeModuleConfig['shotwidth'], $xtubeModuleConfig['shotheight']);
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
 *
 * @return array
 */
function getRandomVideoForHorizontalBlock($options)
{
    global $xtubemyts;
    $moduleDirName = basename(dirname(__DIR__));
    $block         = [];
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler     = xoops_getHandler('module');
    $xtubeModule       = $moduleHandler->getByDirname($moduleDirName);
    $configHandler     = xoops_getHandler('config');
    $xtubeModuleConfig = $configHandler->getConfigsByCat(0, $xtubeModule->getVar('mid'));
    $xtubemyts         = \MyTextSanitizer:: getInstance();

    if (isset($options[4]) && ($options[4] > 0)) {
        $result2 = $GLOBALS['xoopsDB']->query('SELECT lid, cid, title, vidid, screenshot, published, vidsource, picurl FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published > 0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    AND cid=' . $options[4] . '
                                    ORDER BY RAND() LIMIT ' . $options[1]);
    } else {
        $result2 = $GLOBALS['xoopsDB']->query('SELECT lid, cid, title, vidid, screenshot, published, vidsource, picurl FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE published > 0
                                    AND published<=' . time() . '
                                    AND (expired=0 OR expired>' . time() . ')
                                    AND offline=0
                                    ORDER BY RAND() LIMIT ' . $options[1]);
    }

    require_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/include/video.php';
//    require_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/class/Utility.php';

    while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result2))) {
        if (false === checkBlockGroups($myrow['cid']) || 0 == $myrow['cid']) {
            continue;
        }
        $videorandomh            = [];
        $title                   = $xtubemyts->htmlSpecialChars($xtubemyts->stripSlashesGPC($myrow['title']));
        $videorandomh['balloon'] = $myrow['title'];
        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = substr($myrow['title'], 0, $options[2] - 1) . '...';
            }
        }
        $videorandomh['id']    = (int)$myrow['lid'];
        $videorandomh['cid']   = (int)$myrow['cid'];
        $videorandomh['title'] = $title;
        if (isset($options[3])) {
            $videorandomh['date'] = Xoopstube\Utility::getTimestamp(formatTimestamp($myrow['published'], $options[3]));
        }
        require_once XOOPS_ROOT_PATH . '/modules/' . $xtubeModule->getVar('dirname') . '/include/video.php';
        $videorandomh['videothumb'] = xtubeGetVideoThumb($myrow['vidid'], $myrow['title'], $myrow['vidsource'], $myrow['picurl'], $xtubeModuleConfig['videoimgdir'] . '/' . $myrow['screenshot'], $xtubeModuleConfig['shotwidth'], $xtubeModuleConfig['shotheight']);
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
 *
 * @return string
 */
function editTopVideoBlock($options)
{
    $form = '' . _MB_XOOPSTUBE_DISP . '&nbsp;';
    $form .= "<input type='hidden' name='options[]' value='";
    if ('published' === $options[0]) {
        $form .= "published'";
    }
    if ('random' === $options[0]) {
        $form .= "random'";
    }
    if ('randomh' === $options[0]) {
        $form .= "randomh'";
    } else {
        $form .= "hits'";
    }
    $form .= '>';
    $form .= "<input type='text' name='options[]' value='" . $options[1] . "'>&nbsp;" . _MB_XOOPSTUBE_FILES . '';
    $form .= '&nbsp;<br>' . _MB_XOOPSTUBE_CHARS . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "'>&nbsp;" . _MB_XOOPSTUBE_LENGTH . '';
    $form .= '&nbsp;<br>' . _MB_XOOPSTUBE_DATEFORMAT . "&nbsp;<input type='text' name='options[]' value='" . $options[3] . "'>&nbsp;" . _MB_XOOPSTUBE_DATEFORMATMANUAL;

    $cat_arr = [];
    require_once XOOPS_ROOT_PATH . '/modules/xoopstube/class/Tree.php';
    $xt      = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');
    $cat_arr = $xt->getChildTreeArray(0, 'title');

    $form .= '<br>' . _MB_XOOPSTUBE_SELECTCAT . '<br><select name="options[]" multiple="multiple" size="5">';
    $form = false === array_search(0, $options, true) ? $form . '<option value="0">' . _MB_XOOPSTUBE_ALLCAT . '</option>' : $form . '<option value="0" selected="selected">' . _MB_XOOPSTUBE_ALLCAT . '</option>';

    foreach ($cat_arr as $catlist) {
        if (false === array_search($catlist, $options, true)) {
            $form .= '<option value="' . $catlist['cid'] . '">' . $catlist['title'] . '</option>';
        } else {
            $form .= '<option value="' . $catlist['cid'] . '" selected="selected">' . $catlist['title'] . '</option>';
        }
    }
    $form .= '</select>';

    return $form;
}
