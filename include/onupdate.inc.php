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

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

// referer check
$ref = xoops_getenv('HTTP_REFERER');
if ('' === $ref || 0 === strpos($ref, XOOPS_URL . '/modules/system/admin.php')) {
    /* module specific part */

    /* General part */

    // Keep the values of block's options when module is updated (by nobunobu)
    include __DIR__ . '/updateblock.inc.php';
}
