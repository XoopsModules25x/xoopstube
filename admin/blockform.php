<?php
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * PHP version 5
 *
 * @category        Module
 * @author          Fernando Santos (topet05), fernando@mastop.com.br
 * @author          XOOPS Development Team
 * @copyright       XOOPS Project
 * @link            https://xoops.org
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

use Xmf\Module\Admin;
use XoopsModules\Xoopstube\{
    Helper
};

/** @var Admin $adminObject */
/** @var Helper $helper */

require __DIR__ . '/admin_header.php';

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName); //$capsDirName

$helper->loadLanguage('blocksadmin');

$form = new \XoopsThemeForm($block['form_title'], 'blockform', 'blocksadmin.php', 'post', true);
if (isset($block['name'])) {
    $form->addElement(new \XoopsFormLabel(_AM_SYSTEM_BLOCKS_NAME, $block['name']));
}
$sideSelect = new \XoopsFormSelect(_AM_SYSTEM_BLOCKS_TYPE, 'bside', $block['side']);
$sideSelect->addOptionArray(
    [
        0 => _AM_SYSTEM_BLOCKS_SBLEFT,
        1 => _AM_SYSTEM_BLOCKS_SBRIGHT,
        3 => _AM_SYSTEM_BLOCKS_CBLEFT,
        4 => _AM_SYSTEM_BLOCKS_CBRIGHT,
        5 => _AM_SYSTEM_BLOCKS_CBCENTER,
        7 => _AM_SYSTEM_BLOCKS_CBBOTTOMLEFT,
        8 => _AM_SYSTEM_BLOCKS_CBBOTTOMRIGHT,
        9 => _AM_SYSTEM_BLOCKS_CBBOTTOM,
    ]
);
$form->addElement($sideSelect);
$form->addElement(new \XoopsFormText(constant('CO_' . $moduleDirNameUpper . '_' . 'WEIGHT'), 'bweight', 2, 5, $block['weight']));
$form->addElement(new \XoopsFormRadioYN(constant('CO_' . $moduleDirNameUpper . '_' . 'VISIBLE'), 'bvisible', $block['visible']));
$moduleSelect = new \XoopsFormSelect(constant('CO_' . $moduleDirNameUpper . '_' . 'VISIBLEIN'), 'bmodule', $block['modules'], 5, true);
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$criteria      = new \CriteriaCompo(new \Criteria('hasmain', 1));
$criteria->add(new \Criteria('isactive', 1));
$moduleList     = $moduleHandler->getList($criteria);
$moduleList[-1] = _AM_SYSTEM_BLOCKS_TOPPAGE;
$moduleList[0]  = _AM_SYSTEM_BLOCKS_ALLPAGES;
ksort($moduleList);
$moduleSelect->addOptionArray($moduleList);
$form->addElement($moduleSelect);
$form->addElement(new \XoopsFormText(constant('CO_' . $moduleDirNameUpper . '_' . 'TITLE'), 'btitle', 50, 255, $block['title']), false);
if ($block['is_custom']) {
    $textarea = new \XoopsFormDhtmlTextArea(_AM_SYSTEM_BLOCKS_CONTENT, 'bcontent', $block['content'], 15, 70);
    $textarea->setDescription('<span style="font-size:x-small;font-weight:bold;">' . _AM_SYSTEM_BLOCKS_USEFULTAGS . '</span><br><span style="font-size:x-small;font-weight:normal;">' . sprintf(_AM_BLOCKTAG1, '{X_SITEURL}', XOOPS_URL . '/') . '</span>');
    $form->addElement($textarea, true);
    $ctypeSelect = new \XoopsFormSelect(_AM_SYSTEM_BLOCKS_CTYPE, 'bctype', $block['ctype']);
    $ctypeSelect->addOptionArray(
        [
            'H' => _AM_SYSTEM_BLOCKS_HTML,
            'P' => _AM_SYSTEM_BLOCKS_PHP,
            'S' => _AM_SYSTEM_BLOCKS_AFWSMILE,
            'T' => _AM_SYSTEM_BLOCKS_AFNOSMILE,
        ]
    );
    $form->addElement($ctypeSelect);
} else {
    if ('' !== $block['template']) {
        /** @var \XoopsTplfileHandler $tplfileHandler */
        $tplfileHandler = xoops_getHandler('tplfile');
        $btemplate      = $tplfileHandler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $block['bid']);
        if (count($btemplate) > 0) {
            $form->addElement(new \XoopsFormLabel(_AM_SYSTEM_BLOCKS_CONTENT, '<a href="' . XOOPS_URL . '/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id=' . $btemplate[0]->getVar('tpl_id') . '">' . _AM_SYSTEM_BLOCKS_EDITTPL . '</a>'));
        } else {
            $btemplate2 = $tplfileHandler->find('default', 'block', $block['bid']);
            if (count($btemplate2) > 0) {
                $form->addElement(new \XoopsFormLabel(_AM_SYSTEM_BLOCKS_CONTENT, '<a href="' . XOOPS_URL . '/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id=' . $btemplate2[0]->getVar('tpl_id') . '" target="_blank">' . _AM_SYSTEM_BLOCKS_EDITTPL . '</a>'));
            }
        }
    }
    if (false !== $block['edit_form']) {
        $form->addElement(new \XoopsFormLabel(_AM_SYSTEM_BLOCKS_OPTIONS, $block['edit_form']));
    }
}
$cacheSelect = new \XoopsFormSelect(_AM_SYSTEM_BLOCKS_BCACHETIME, 'bcachetime', $block['bcachetime']);
$cacheSelect->addOptionArray(
    [
        '0'       => _NOCACHE,
        '30'      => sprintf(_SECONDS, 30),
        '60'      => _MINUTE,
        '300'     => sprintf(_MINUTES, 5),
        '1800'    => sprintf(_MINUTES, 30),
        '3600'    => _HOUR,
        '18000'   => sprintf(_HOURS, 5),
        '86400'   => _DAY,
        '259200'  => sprintf(_DAYS, 3),
        '604800'  => _WEEK,
        '2592000' => _MONTH,
    ]
);
$form->addElement($cacheSelect);

/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$groups           = $grouppermHandler->getGroupIds('block_read', $block['bid']);

$form->addElement(new \XoopsFormSelectGroup(_AM_SYSTEM_BLOCKS_GROUP, 'groups', true, $groups, 5, true));

if (isset($block['bid'])) {
    $form->addElement(new \XoopsFormHidden('bid', $block['bid']));
}
$form->addElement(new \XoopsFormHidden('op', $block['op']));
$form->addElement(new \XoopsFormHidden('fct', 'blocksadmin'));
$buttonTray = new \XoopsFormElementTray('', '&nbsp;');
if ($block['is_custom']) {
    $buttonTray->addElement(new \XoopsFormButton('', 'previewblock', _PREVIEW, 'submit'));
}

//Submit buttons
$buttonTray    = new \XoopsFormElementTray('', '');
$submitButton = new \XoopsFormButton('', 'submitblock', _SUBMIT, 'submit');
$buttonTray->addElement($submitButton);

$cancelButton = new \XoopsFormButton('', '', _CANCEL, 'button');
$cancelButton->setExtra('onclick="history.go(-1)"');
$buttonTray->addElement($cancelButton);

$form->addElement($buttonTray);
