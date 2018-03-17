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

$op  = Request::getCmd('op', Request::getCmd('op', '', 'POST'), 'GET'); //cleanRequestVars($_REQUEST, 'op', '');
$lid = Request::getInt('lid', Request::getInt('lid', 0, 'POST'), 'GET'); //cleanRequestVars($_REQUEST, 'lid', 0);

/**
 * @param Xoopstube\Tree          $xt
 * @param int            $itemid
 * @param                $title
 * @param                $checks
 * @param string         $order
 */
function makeTreeCheckTable(Xoopstube\Tree $xt, $itemid, $title, $checks, $order = '')
{
    global $xtubemyts;

    echo '<div style="text-align: left;">';
    echo '<form name="altcat" method="post" action="' . xoops_getenv('PHP_SELF') . '">';
    echo '<table width="100%" callspacing="1" class="outer">';
    $sql = 'SELECT ' . $xt->id . ', ' . $title . ' FROM ' . $xt->table . ' WHERE ' . $xt->pid . '=0' . ' ORDER BY ' . $title;
    if ('' !== $order) {
        $sql .= ' ORDER BY ' . $order;
    }
    $result = $xt->db->query($sql);

    while (false !== (list($cid, $name) = $xt->db->fetchRow($result))) {
        $checked  = array_key_exists($cid, $checks) ? 'checked' : '';
        $disabled = ($cid === Request::getInt('cid', 0, 'GET')) ? "disabled='yes'" : '';
        $level    = 1;
        echo '
        <tr style="text-align: left;">
         <td width="30%" class="head">' . $name . '</td>
         <td class="head">
             <input type="checkbox" name="cid-' . $cid . '" value="0" ' . $checked . ' ' . $disabled . '>
         </td>
        </tr>';
        $arr = $xt->getChildTreeArray($cid, $order);
        foreach ($arr as $cat) {
            $cat['prefix'] = str_replace('.', '-', $cat['prefix']);
            $catpath       = '&nbsp;' . $cat['prefix'] . '&nbsp;' . $xtubemyts->htmlSpecialCharsStrip($cat[$title]);
            $checked       = array_key_exists($cat['cid'], $checks) ? 'checked' : '';
            $disabled      = ($cat['cid'] === Request::getInt('cid', 0, 'GET')) ? "disabled='yes'" : '';
            $level         = substr_count($cat['prefix'], '-') + 1;
            //          echo "<tr><td>" . $catpath . "<input type='checkbox' name='cid-" . $cat['cid'] . "' value='0' " . $checked . " " . $disabled . "></td></tr>\n";
            echo '
        <tr style="text-align: left;">
         <td width="30%" class="even">' . $catpath . '</td>
         <td class="even">
             <input type="checkbox" name="cid-' . $cat['cid'] . '" value="0" ' . $checked . ' ' . $disabled . '>
         </td>
        </tr>';
        }
    }
    echo '<tr>
           <td width="30%"></td>
           <td style="text-align: left;">
            <input type="submit" class="mainbutton" value="save">
            <input type="hidden" name="op" value="save">
            <input type="hidden" name="lid" value="' . $itemid . '">
            </td>
          </tr>';
    echo '</table></form></div>';
}

switch (strtolower($op)) {
    case 'save':
        // first delete all alternate categories for this topic
        $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_altcat') . ' WHERE lid=' . $lid;
        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            /** @var \XoopsLogger $logger */
            $logger = \XoopsLogger::getInstance();
            $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

            return false;
        }

        $k = array_keys($_REQUEST);
        foreach ($k as $sid) {
            if (preg_match('/cid-(\d*)/', $sid, $cid)) {
                $sql = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('xoopstube_altcat') . '(cid, lid) VALUES("' . $cid[1] . '","' . $lid . '")';
                if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                    $logger->handleError(E_USER_WARNING, $sql, __FILE__, __LINE__);

                    return false;
                }
            }
        }
        redirect_header('index.php', 1, _AM_XOOPSTUBE_ALTCAT_CREATED);
        break;

    case 'main':
    default:
        xoops_cp_header();
        //renderAdminMenu(_AM_XOOPSTUBE_MALTCAT);
        echo '<fieldset><legend style="font-weight: bold; color: #0A3760;">' . _AM_XOOPSTUBE_ALTCAT_MODIFYF . '</legend>
          <div style="padding: 8px;">' . _AM_XOOPSTUBE_ALTCAT_INFOTEXT . '</div>
          </fieldset>';

        echo '<div style="text-align: left;"><h3> ' . $_REQUEST['title'] . ' </h3></div>';
        // Get an array of all alternate categories for this topic
        $sql     = $GLOBALS['xoopsDB']->query('SELECT cid FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_altcat') . ' WHERE lid="' . $lid . '" ORDER BY lid');
        $altcats = [];
        while (false !== ($altcat = $GLOBALS['xoopsDB']->fetchArray($sql))) {
            $altcats[$altcat['cid']] = true;
        }
        $mytree = new Xoopstube\Tree($GLOBALS['xoopsDB']->prefix('xoopstube_cat'), 'cid', 'pid');
        makeTreeCheckTable($mytree, $lid, 'title', $altcats);
        xoops_cp_footer();
}
