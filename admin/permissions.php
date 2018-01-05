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
require_once __DIR__ . '/../../../include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));

$permtoset                = Request::getInt('permtoset', 1, 'POST');// isset($_POST['permtoset']) ? (int) $_POST['permtoset'] : 1;
$selected                 = ['', '', '', '', ''];
$selected[$permtoset - 1] = ' selected';
echo "<form method='post' name='fselperm' action='permissions.php'><table border=0><tr><td><select name='permtoset' onChange='document.fselperm.submit()'>
<option value='1'" . $selected[0] . '>' . _AM_XOOPSTUBE_PERM_CPERMISSIONS . "</option>
<option value='2'" . $selected[1] . '>' . _AM_XOOPSTUBE_PERM_SPERMISSIONS . "</option>
<option value='3'" . $selected[2] . '>' . _AM_XOOPSTUBE_PERM_APERMISSIONS . "</option>
<option value='4'" . $selected[3] . '>' . _AM_XOOPSTUBE_PERM_AUTOPERMISSIONS . "</option>
<option value='5'" . $selected[4] . '>' . _AM_XOOPSTUBE_PERM_RATEPERMISSIONS . '</option>
</select></td></tr><tr><td><br></td></tr></table></form>';
$module_id = $xoopsModule->getVar('mid');

switch ($permtoset) {
    case 1:
        $title_of_form = _AM_XOOPSTUBE_PERM_CPERMISSIONS;
        $perm_name     = 'XTubeCatPerm';
        $perm_desc     = _AM_XOOPSTUBE_PERM_CSELECTPERMISSIONS;
        break;
    case 2:
        $title_of_form = _AM_XOOPSTUBE_PERM_SPERMISSIONS;
        $perm_name     = 'XTubeSubPerm';
        $perm_desc     = _AM_XOOPSTUBE_PERM_SPERMISSIONS_TEXT;
        break;
    case 3:
        $title_of_form = _AM_XOOPSTUBE_PERM_APERMISSIONS;
        $perm_name     = 'XTubeAppPerm';
        $perm_desc     = _AM_XOOPSTUBE_PERM_APERMISSIONS_TEXT;
        break;
    case 4:
        $title_of_form = _AM_XOOPSTUBE_PERM_AUTOPERMISSIONS;
        $perm_name     = 'XTubeAutoApp';
        $perm_desc     = _AM_XOOPSTUBE_PERM_AUTOPERMISSIONS_TEXT;
        break;
    case 5:
        $title_of_form = _AM_XOOPSTUBE_PERM_RATEPERMISSIONS;
        $perm_name     = 'XTubeRatePerms';
        $perm_desc     = _AM_XOOPSTUBE_PERM_RATEPERMISSIONS_TEXT;
        break;
}

$permform = new \XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/permissions.php');
$result   = $GLOBALS['xoopsDB']->query('SELECT cid, pid, title FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' ORDER BY title ASC');
if ($GLOBALS['xoopsDB']->getRowsNum($result)) {
    while (false !== ($permrow = $GLOBALS['xoopsDB']->fetcharray($result))) {
        $permform->addItem($permrow['cid'], '&nbsp;' . $permrow['title'], $permrow['pid']);
    }
    echo $permform->render();
} else {
    echo '<div><b>' . _AM_XOOPSTUBE_PERM_CNOCATEGORY . '</b></div>';
}
unset($permform);

echo _AM_XOOPSTUBE_PERM_PERMSNOTE . '<br>';

require_once __DIR__ . '/admin_footer.php';
