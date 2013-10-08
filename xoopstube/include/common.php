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
 * @author          XOOPS Development Team, Irmtfan
 * @copyright       2001-2013 The XOOPS Project
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version         $Id: common.php 11722 2013-06-19 16:47:58Z beckmi $
 * @link            http://sourceforge.net/projects/xoops/
 * @since           1.0.6
 */

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

define("XTUBE_DIRNAME", basename(dirname(dirname(__FILE__))));
define("XTUBE_URL", XOOPS_URL . '/modules/' . XTUBE_DIRNAME);
define("XTUBE_ADMIN_URL", XTUBE_URL . '/admin');
define("XTUBE_ROOT_PATH", XOOPS_ROOT_PATH . '/modules/' . XTUBE_DIRNAME);

//xoops_load("xoopsuserutility");
//xoops_load("XoopsCache");
//xoops_load("XoopsFile");
