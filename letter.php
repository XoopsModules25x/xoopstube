<?php
/**
 * Module: Xooopstube
 * Licence: GNU
 */

use Xmf\Request;
use XoopsModules\Xoopstube\{
    Helper,
    Utility
};
/** @var Helper $helper */

require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'lx_letter.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once XOOPS_ROOT_PATH . '/modules/lexikon/include/common.inc.php';
$helper  = Helper::getInstance();
$utility = new Utility();

global $xoTheme, $xoopsUser;
$myts = \MyTextSanitizer::getInstance();

$init = Request::getString('init', 0, 'GET');
$xoopsTpl->assign('firstletter', $init);
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
$start = Request::getInt('start', 0, 'GET');

$publishedwords = $utility::countWords();
$xoopsTpl->assign('publishedwords', $publishedwords);

//permissions
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$groups           = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id        = $xoopsModule->getVar('mid');
$allowed_cats     = $grouppermHandler->getItemIds('lexikon_view', $groups, $module_id);
$catids           = implode(',', $allowed_cats);
$catperms         = " AND categoryID IN ($catids) ";

$xoopsTpl->assign('multicats', (int)$helper->getConfig('multicats'));

if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
    /**
     * @param $string
     * @return false|string
     */
    function mb_ucfirst($string)
    {
        $string = mb_ereg_replace('^[\ ]+', '', $string);
        $string = mb_strtoupper(mb_substr($string, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($string, 1, mb_strlen($string), 'UTF-8');

        return $string;
    }
}
// To display the linked letter list
$alpha = $utility::getAlphaArray();
$xoopsTpl->assign('alpha', $alpha);

[$howmanyother] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxentries') . " WHERE init = '#' AND offline ='0' " . $catperms . ' '));
$xoopsTpl->assign('totalother', $howmanyother);

// To display the list of categories
if (1 == $helper->getConfig('multicats')) {
    $xoopsTpl->assign('block0', $utility::getCategoryArray());
    $xoopsTpl->assign('layout', CONFIG_CATEGORY_LAYOUT_PLAIN);
    if ($utility::getModuleOption('useshots')) {
        $xoopsTpl->assign('show_screenshot', true);
        $xoopsTpl->assign('logo_maximgwidth', $helper->getConfig('logo_maximgwidth'));
        $xoopsTpl->assign('lang_noscreenshot', _MD_LEXIKON_NOSHOTS);
    } else {
        $xoopsTpl->assign('show_screenshot', false);
    }
}

// No initial: we need to see all letters
if (!$init) {
    $entriesarray = [];
    $pagetype     = 0;

    // How many entries will we show in this page?
    //$queryA = "SELECT w. * , c.name AS catname FROM ".$xoopsDB -> prefix( 'lxentries' )." w LEFT JOIN ".$xoopsDB -> prefix( 'lxcategories' )." c ON w.categoryID = c.categoryID WHERE w.submit = '0' AND w.offline = '0' ORDER BY w.term ASC";
    //$resultA = $xoopsDB -> query ($queryA, $helper->getConfig('indexperpage'), $start );
    $queryA  = 'SELECT * FROM ' . $xoopsDB->prefix('lxentries') . " WHERE offline = '0' AND submit = '0' " . $catperms . ' ORDER BY term ASC';
    $resultA = $xoopsDB->query($queryA, $helper->getConfig('indexperpage'), $start);

    $allentries   = $xoopsDB->query('SELECT entryID FROM ' . $xoopsDB->prefix('lxentries') . " WHERE submit ='0' AND offline = '0' " . $catperms . ' ORDER BY term ASC ');
    $totalentries = $xoopsDB->getRowsNum($allentries);
    $xoopsTpl->assign('totalentries', $totalentries);

    while (list($entryID, $categoryID, $term, $init, $definition, $ref, $url, $uid, $submit, $datesub, $counter, $html, $smiley, $xcodes, $breaks, $block, $offline, $comments) = $xoopsDB->fetchRow($resultA)) {
        $eachentry        = [];
        $xoopsModule      = XoopsModule::getByDirname('lexikon');
        $eachentry['dir'] = $xoopsModule->dirname();

        if (1 == $helper->getConfig('multicats')) {
            $eachentry['catid'] = (int)$categoryID;
            $resultF            = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('lxcategories') . " WHERE categoryID = $categoryID ORDER BY name ASC");
            while (list($name) = $xoopsDB->fetchRow($resultF)) {
                $eachentry['catname'] = htmlspecialchars($name);
            }
        }

        $eachentry['id']   = (int)$entryID;
        $eachentry['term'] = mb_ucfirst(htmlspecialchars($term));

        if ((0 != $helper->getConfig('com_rule')) || ((0 != $helper->getConfig('com_rule')) && is_object($xoopsUser))) {
            if (0 != $comments) {
                $eachentry['comments'] = "<a href='entry.php?entryID=" . $eachentry['id'] . "'>" . $comments . '&nbsp;' . _COMMENTS . '</a>';
            } else {
                $eachentry['comments'] = '';
            }
        }

        if (!XOOPS_USE_MULTIBYTES) {
            $eachentry['definition'] = $myts->displayTarea($definition, $html, $smiley, $xcodes, 1, $breaks);
        }

        // Functional links
        $microlinks              = $utility::getServiceLinks($eachentry);
        $eachentry['microlinks'] = $microlinks;

        $entriesarray['single'][] = $eachentry;
    }
    $pagenav                = new \XoopsPageNav($totalentries, $helper->getConfig('indexperpage'), $start, 'start');
    $entriesarray['navbar'] = '<div style="text-align:right;">' . $pagenav->renderNav(6) . '</div>';

    $xoopsTpl->assign('entriesarray', $entriesarray);
    $xoopsTpl->assign('pagetype', '0');
    $xoopsTpl->assign('pageinitial', _MD_LEXIKON_ALL);

    $utility::createPageTitle(htmlspecialchars(_MD_LEXIKON_BROWSELETTER . ' - ' . _MD_LEXIKON_ALL));
} else {    // $init does exist
    $pagetype = 1;
    // There IS an initial letter, so we want to show just that letter's terms
    $entriesarray2 = [];

    // How many entries will we show in this page?
    if (_MD_LEXIKON_OTHER == $init) {
        $queryB  = 'SELECT entryID, categoryID, term, definition, uid, html, smiley, xcodes, breaks, comments FROM ' . $xoopsDB->prefix('lxentries') . " WHERE submit ='0' AND offline = '0' AND init = '#' " . $catperms . '  ORDER BY term ASC';
        $resultB = $xoopsDB->query($queryB, $helper->getConfig('indexperpage'), $start);
    } else {
        $queryB  = 'SELECT entryID, categoryID, term, definition, uid, html, smiley, xcodes, breaks, comments FROM ' . $xoopsDB->prefix('lxentries') . " WHERE submit ='0' AND offline = '0' AND init = '$init' AND init != '#' " . $catperms . '  ORDER BY term ASC';
        $resultB = $xoopsDB->query($queryB, $helper->getConfig('indexperpage'), $start);
    }

    $entrieshere = $xoopsDB->getRowsNum($resultB);
    if (0 == $entrieshere) {
        redirect_header('<script>javascript:history.go(-1)</script>', 1, _MD_LEXIKON_NOTERMSINLETTER);
    }

    if (_MD_LEXIKON_OTHER == $init) {
        $allentries = $xoopsDB->query('SELECT entryID FROM ' . $xoopsDB->prefix('lxentries') . " WHERE init = '#' AND submit ='0' AND offline = '0' " . $catperms . '  ORDER BY term ASC ');
    } else {
        $allentries = $xoopsDB->query('SELECT entryID FROM ' . $xoopsDB->prefix('lxentries') . " WHERE init = '$init' AND init != '#' AND submit ='0' AND offline = '0' " . $catperms . '  ORDER BY term ASC ');
    }
    $totalentries = $xoopsDB->getRowsNum($allentries);
    $xoopsTpl->assign('totalentries', $totalentries);
    $utility::createPageTitle(htmlspecialchars(_MD_LEXIKON_BROWSELETTER . (isset($init['init']) ? (' - ' . $init['init']) : '')));

    while (list($entryID, $categoryID, $term, $definition, $uid, $html, $smiley, $xcodes, $breaks, $comments) = $xoopsDB->fetchRow($resultB)) {
        $eachentry        = [];
        $xoopsModule      = XoopsModule::getByDirname('lexikon');
        $eachentry['dir'] = $xoopsModule->dirname();

        if (1 == $helper->getConfig('multicats')) {
            $eachentry['catid'] = (int)$categoryID;
            $resultF            = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('lxcategories') . " WHERE categoryID = $categoryID ORDER BY name ASC");
            while (list($name) = $xoopsDB->fetchRow($resultF)) {
                $eachentry['catname'] = htmlspecialchars($name);
            }
        }
        $eachentry['id']   = (int)$entryID;
        $eachentry['term'] = mb_ucfirst(htmlspecialchars($term));
        if ('#' === $init) {
            $eachentry['init'] = _MD_LEXIKON_OTHER;
        } else {
            $eachentry['init'] = $init;
        }

        if ((0 != $helper->getConfig('com_rule')) || ((0 != $helper->getConfig('com_rule')) && is_object($xoopsUser))) {
            if (0 != $comments) {
                $eachentry['comments'] = "<a href='entry.php?entryID=" . $eachentry['id'] . "'>" . $comments . '&nbsp;' . _COMMENTS . '</a>';
            } else {
                $eachentry['comments'] = '';
            }
        }
        if (!XOOPS_USE_MULTIBYTES) {
            $eachentry['definition'] = $myts->displayTarea($definition, $html, $smiley, $xcodes, 1, $breaks);
        }

        // Functional links
        $microlinks              = $utility::getServiceLinks($eachentry);
        $eachentry['microlinks'] = $microlinks;

        $entriesarray2['single'][] = $eachentry;
    }
    $pagenav                 = new \XoopsPageNav($totalentries, $helper->getConfig('indexperpage'), $start, 'init=' . $eachentry['init'] . '&start');
    $entriesarray2['navbar'] = '<div style="text-align:right;">' . $pagenav->renderNav(6) . '</div>';

    $xoopsTpl->assign('entriesarray2', $entriesarray2);
    $xoopsTpl->assign('pagetype', '1');
    if ('#' === $eachentry['init']) {
        $xoopsTpl->assign('pageinitial', _MD_LEXIKON_OTHER);
        $utility::createPageTitle(htmlspecialchars(_MD_LEXIKON_BROWSELETTER . ' - ' . _MD_LEXIKON_OTHER));
    } else {
        $xoopsTpl->assign('pageinitial', mb_ucfirst($eachentry['init']));
    }
}

$xoopsTpl->assign('lang_modulename', $xoopsModule->name());
$xoopsTpl->assign('lang_moduledirname', $xoopsModule->getVar('dirname'));
$xoopsTpl->assign('alpha', $alpha);
if (1 == $helper->getConfig('syndication')) {
    $xoopsTpl->assign('syndication', true);
}
if ($xoopsUser) {
    $xoopsTpl->assign('syndication', true);
}
// Meta data
if (0 == $publishedwords) {
    $meta_description = xoops_substr($utility::convertHtml2text($eachentry['definition']), 0, 150);
    if (1 == $helper->getConfig('multicats')) {
        $utility::extractKeywords($xoopsModule->name() . ' ,' . $eachentry['term'] . ', ' . $meta_description);
        $utility::getMetaDescription(htmlspecialchars($xoopsModule->name()) . ' ' . $eachentry['catname'] . ' ' . $eachentry['term']);
    } else {
        $utility::extractKeywords(htmlspecialchars($xoopsModule->name()) . ', ' . $eachentry['term'] . ', ' . $meta_description);
        $utility::getMetaDescription(htmlspecialchars($xoopsModule->name()) . ' ' . $eachentry['term'] . ' ' . $meta_description);
    }
}

$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="assets/css/style.css">');

require_once XOOPS_ROOT_PATH . '/footer.php';
