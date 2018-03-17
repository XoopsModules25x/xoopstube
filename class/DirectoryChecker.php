<?php namespace XoopsModules\Xoopstube;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Xoopstube module
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 */

use Xmf\Request;

//defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once __DIR__ . '/../../../mainfile.php';

/**
 * Class DirectoryChecker
 * check status of a directory
 */
class DirectoryChecker
{
    /**
     * @param       $path
     * @param int   $mode
     * @param array $languageConstants
     * @param       $redirectFile
     *
     * @return bool|string
     */
    public static function getDirectoryStatus($path, $mode = 0777, array $languageConstants = [], $redirectFile)
    {
        global $pathIcon16;

        $languageConstants1 = [$languageConstants[5], $languageConstants[6]];
        $languageConstants2 = [$languageConstants[7], $languageConstants[8]];

        $myWords1 = urlencode(json_encode($languageConstants1));
        $myWords2 = urlencode(json_encode($languageConstants2));

        if (empty($path)) {
            return false;
        }
        if (!@is_dir($path)) {
            $path_status = "<img src='" . $pathIcon16 . "/0.png'   >" . $path . ' ( ' . $languageConstants[1] . ' ) ' . '<a href=' . $_SERVER['PHP_SELF'] . "?op=dashboard&dircheck=createdir&amp;path=$path&amp;redirect=$redirectFile&amp;languageConstants=$myWords1>" . $languageConstants[2] . '</a>';
        } elseif (@is_writable($path)) {
            $path_status = "<img src='" . $pathIcon16 . "/1.png'   >" . $path . ' ( ' . $languageConstants[0] . ' ) ';
            $currentMode = substr(decoct(fileperms($path)), 2);
            if ($currentMode !== decoct($mode)) {
                $path_status = "<img src='"
                               . $pathIcon16
                               . "/0.png'   >"
                               . $path
                               . sprintf($languageConstants[3], decoct($mode), $currentMode)
                               . '<a href='
                               . $_SERVER['PHP_SELF']
                               . "?op=dashboard&dircheck=setperm&amp;mode=$mode&amp;path=$path&amp;redirect=$redirectFile&amp;languageConstants=$myWords2> "
                               . $languageConstants[4]
                               . '</a>';
            }
        } else {
            $currentMode = substr(decoct(fileperms($path)), 2);
            $path_status = "<img src='" . $pathIcon16 . "/0.png'   >" . $path . sprintf($languageConstants[3], decoct($mode), $currentMode) . '<a href=' . $_SERVER['PHP_SELF'] . "?mode&amp;path=$path&amp;redirect=$redirectFile&amp;languageConstants=$myWords2> " . $languageConstants[4] . '</a>';
        }

        return $path_status;
    }

    /**
     * @param     $target
     * @param int $mode
     *
     * @return bool
     */
    public static function createDirectory($target, $mode = 0777)
    {
        $target = str_replace('..', '', $target);
        // http://www.php.net/manual/en/function.mkdir.php
        /*
                $dirs = array();

                while (realpath($target) === false && !empty($target)) {
                    array_unshift($dirs, basename($target));
                    $target = dirname($target);
                };
                $target = realpath($target);
        // this next test should handle bug on BSD with PHP < 5.3.0
                if (!is_dir($target)) {
                    array_unshift($dirs, basename($target));
                    $target = realpath(dirname($target));
                }

        */

        return is_dir($target) || (self::createDirectory(dirname($target), $mode) && mkdir($target, $mode));
    }

    /**
     * @param     $target
     * @param int $mode
     *
     * @return bool
     */
    public static function setDirectoryPermissions($target, $mode = 0777)
    {
        $target = str_replace('..', '', $target);

        return @chmod($target, (int)$mode);
    }
}

$dircheck = Request::getString('dircheck', '', 'GET') ? filter_input(INPUT_GET, 'dircheck', FILTER_SANITIZE_STRING) : '';

switch ($dircheck) {
    case 'createdir':
        $languageConstants = [];
        if (Request::hasVar('path', 'GET')) {
            $path = filter_input(INPUT_GET, 'path', FILTER_SANITIZE_STRING);
        }
        if (Request::hasVar('redirect', 'GET')) {
            $redirect = filter_input(INPUT_GET, 'redirect', FILTER_SANITIZE_STRING);
        }
        if (Request::hasVar('languageConstants', 'GET')) {
            $languageConstants = json_decode(Request::getString('languageConstants', '', 'GET'));
        }
        $result = DirectoryChecker::createDirectory($path);
        $msg    = $result ? $languageConstants[0] : $languageConstants[1];
        redirect_header($redirect, 2, $msg . ': ' . $path);

        break;
    case 'setperm':
        $languageConstants = [];
        if (Request::hasVar('path', 'GET')) {
            $path = filter_input(INPUT_GET, 'path', FILTER_SANITIZE_STRING);
        }
        if (Request::hasVar('mode', 'GET')) {
            $mode = filter_input(INPUT_GET, 'mode', FILTER_SANITIZE_STRING);
        }
        if (Request::hasVar('redirect', 'GET')) {
            $redirect = filter_input(INPUT_GET, 'redirect', FILTER_SANITIZE_STRING);
        }
        if (Request::hasVar('languageConstants', 'GET')) {
            $languageConstants = json_decode(Request::getString('languageConstants', '', 'GET'));
        }
        $result = DirectoryChecker::setDirectoryPermissions($path, $mode);
        $msg    = $result ? $languageConstants[0] : $languageConstants[1];
        redirect_header($redirect, 2, $msg . ': ' . $path);

        break;
}
