<?php

namespace XoopsModules\Xoopstube;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Criteria;
use CriteriaCompo;
use MyTextSanitizer;
use WideImage\WideImage;
use Xmf\Request;
use XoopsDatabaseFactory;
use XoopsModule;
use XoopsModules\Xoopstube;
use XoopsModules\Xoopstube\Common;
use XoopsModules\Xoopstube\Constants;
use XoopsObject;
use XoopsPageNav;
use XoopsTpl;
use xos_opal_Theme;

use const _AM_XOOPSTUBE_VIDSOURCE2;
use const _AM_XOOPSTUBE_WARNINSTALL1;
use const _AM_XOOPSTUBE_WARNINSTALL2;
use const _AM_XOOPSTUBE_WARNINSTALL3;
use const _AM_XOOPSTUBE_WARNINSTALL4;
use const ENT_HTML5;

/**
 * xoopstube
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hervé Thouzard (http://www.herve-thouzard.com/)
 */

/**
 * A set of useful and common functions
 *
 * @package       xoopstube
 * @author        Hervé Thouzard - Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 *
 * Note: You should be able to use it without the need to instantiate it.
 */

//require_once  dirname(__DIR__) . '/include/common.php';

/**
 * Class Utility
 */
class Utility extends Common\SysUtility
{
    //--------------- Custom module methods -----------------------------

    public const MODULE_NAME = 'xoopstube';

    /**
     * Access the only instance of this class
     *
     * @return \XoopsModules\Xoopstube\Utility
     *
     * @static
     * @staticvar   object
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Returns a module's option (with cache)
     *
     * @param string $option    module option's name
     * @param bool   $withCache Do we have to use some cache ?
     *
     * @return mixed option's value
     */
    public static function getModuleOption($option, $withCache = true)
    {
        global $xoopsModuleConfig, $xoopsModule;
        $repmodule = self::MODULE_NAME;
        static $options = [];
        if (is_array($options) && array_key_exists($option, $options) && $withCache) {
            return $options[$option];
        }

        $retval = false;
        if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule && $xoopsModule->getVar('isactive'))) {
            if (isset($xoopsModuleConfig[$option])) {
                $retval = $xoopsModuleConfig[$option];
            }
        } else {
            /** @var \XoopsModuleHandler $moduleHandler */
            $moduleHandler = xoops_getHandler('module');
            $module        = $moduleHandler->getByDirname($repmodule);
            /** @var \XoopsConfigHandler $configHandler */
            $configHandler = xoops_getHandler('config');
            if ($module) {
                $moduleConfig = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
                if (isset($moduleConfig[$option])) {
                    $retval = $moduleConfig[$option];
                }
            }
        }
        $options[$option] = $retval;

        return $retval;
    }

    /**
     * Is Xoops 2.3.x ?
     *
     * @return bool
     */
    public static function isX23()
    {
        $x23 = false;
        $xv  = str_replace('XOOPS ', '', XOOPS_VERSION);
        if ((int)mb_substr($xv, 2, 1) >= 3) {
            $x23 = true;
        }

        return $x23;
    }

    /**
     * Is Xoops 2.0.x ?
     *
     * @return bool
     */
    public static function isX20()
    {
        $x20 = false;
        $xv  = str_replace('XOOPS ', '', XOOPS_VERSION);
        if ('0' == mb_substr($xv, 2, 1)) {
            $x20 = true;
        }

        return $x20;
    }

    /**
     * Create (in a link) a javascript confirmation's box
     *
     * @param string $message Message to display
     * @param bool   $form    Is this a confirmation for a form ?
     *
     * @return string the javascript code to insert in the link (or in the form)
     */
    public static function javascriptLinkConfirm($message, $form = false)
    {
        if (!$form) {
            return "onclick=\"javascript:return confirm('" . str_replace("'", ' ', $message) . "')\"";
        }

        return "onSubmit=\"javascript:return confirm('" . str_replace("'", ' ', $message) . "')\"";
    }

    /**
     * Get current user IP
     *
     * @return string IP address (format Ipv4)
     */
    public static function IP()
    {
        $proxy_ip = '';
        if (Request::hasVar('HTTP_X_FORWARDED_FOR', 'SERVER')) {
            $proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
            $proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            $proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
            $proxy_ip = $_SERVER['HTTP_FORWARDED'];
        } elseif (!empty($_SERVER['HTTP_VIA'])) {
            $proxy_ip = $_SERVER['HTTP_VIA'];
        } elseif (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
            $proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
        } elseif (!empty($_SERVER['HTTP_COMING_FROM'])) {
            $proxy_ip = $_SERVER['HTTP_COMING_FROM'];
        }
        $regs = [];
        //if (!empty($proxy_ip) && $is_ip = ereg('^([0-9]{1,3}\.){3,3}[0-9]{1,3}', $proxy_ip, $regs) && count($regs) > 0) {
        if (!empty($proxy_ip) && filter_var($proxy_ip, FILTER_VALIDATE_IP) && count($regs) > 0) {
            $the_IP = $regs[0];
        } else {
            $the_IP = $_SERVER['REMOTE_ADDR'];
        }

        return $the_IP;
    }

    /**
     * Set the page's title, meta description and meta keywords
     * Datas are supposed to be sanitized
     *
     * @param string $pageTitle       Page's Title
     * @param string $metaDescription Page's meta description
     * @param string $metaKeywords    Page's meta keywords
     */
    public static function setMetas($pageTitle = '', $metaDescription = '', $metaKeywords = '')
    {
        global $xoTheme, $xoTheme, $xoopsTpl;
        $xoopsTpl->assign('xoops_pagetitle', $pageTitle);
        if (isset($xoTheme) && is_object($xoTheme)) {
            if (!empty($metaKeywords)) {
                $xoTheme->addMeta('meta', 'keywords', $metaKeywords);
            }
            if (!empty($metaDescription)) {
                $xoTheme->addMeta('meta', 'description', $metaDescription);
            }
        } elseif (isset($xoopsTpl) && is_object($xoopsTpl)) { // Compatibility for old Xoops versions
            if (!empty($metaKeywords)) {
                $xoopsTpl->assign('xoops_meta_keywords', $metaKeywords);
            }
            if (!empty($metaDescription)) {
                $xoopsTpl->assign('xoops_meta_description', $metaDescription);
            }
        }
    }

    /**
     * Send an email from a template to a list of recipients
     *
     * @param        $tplName
     * @param array  $recipients List of recipients
     * @param string $subject    Email's subject
     * @param array  $variables  Varirables to give to the template
     *
     * @return bool Result of the send
     * @internal param string $tpl_name Template's name
     */
    public static function sendEmailFromTpl($tplName, $recipients, $subject, $variables)
    {
        global $xoopsConfig;
        require_once XOOPS_ROOT_PATH . '/class/xoopsmailer.php';
        if (!is_array($recipients)) {
            if ('' === trim($recipients)) {
                return false;
            }
        } elseif (0 == count($recipients)) {
            return false;
        }
        if (function_exists('xoops_getMailer')) {
            $xoopsMailer = xoops_getMailer();
        } else {
            $xoopsMailer = getMailer();
        }

        $xoopsMailer->useMail();
        $templateDir = XOOPS_ROOT_PATH . '/modules/' . self::MODULE_NAME . '/language/' . $xoopsConfig['language'] . '/mail_template';
        if (!is_dir($templateDir)) {
            $templateDir = XOOPS_ROOT_PATH . '/modules/' . self::MODULE_NAME . '/language/english/mail_template';
        }
        $xoopsMailer->setTemplateDir($templateDir);
        $xoopsMailer->setTemplate($tplName);
        $xoopsMailer->setToEmails($recipients);
        // TODO: Change !
        // $xoopsMailer->setFromEmail('contact@monsite.com');
        //$xoopsMailer->setFromName('MonSite');
        $xoopsMailer->setSubject($subject);
        foreach ($variables as $key => $value) {
            $xoopsMailer->assign($key, $value);
        }
        $res = $xoopsMailer->send();
        unset($xoopsMailer);
        $filename = XOOPS_UPLOAD_PATH . '/logmail_' . self::MODULE_NAME . '.php';
        if (!is_file($filename)) {
            $fp = @fopen($filename, 'ab');
            if ($fp) {
                fwrite($fp, "<?php exit(); ?>\n");
                fclose($fp);
            }
        }
        $fp = @fopen($filename, 'ab');

        if ($fp) {
            fwrite($fp, str_repeat('-', 120) . "\n");
            fwrite($fp, date('d/m/Y H:i:s') . "\n");
            fwrite($fp, 'Template name : ' . $tplName . "\n");
            fwrite($fp, 'Email subject : ' . $subject . "\n");
            if (is_array($recipients)) {
                fwrite($fp, 'Recipient(s) : ' . implode(',', $recipients) . "\n");
            } else {
                fwrite($fp, 'Recipient(s) : ' . $recipients . "\n");
            }
            fwrite($fp, 'Transmited variables : ' . implode(',', $variables) . "\n");
            fclose($fp);
        }

        return $res;
    }

    /**
     * Remove module's cache
     */
    public static function updateCache()
    {
        global $xoopsModule;
        $folder  = $xoopsModule->getVar('dirname');
        $tpllist = [];
        require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
        require_once XOOPS_ROOT_PATH . '/class/template.php';
        $tplfileHandler = xoops_getHandler('tplfile');
        $tpllist        = $tplfileHandler->find(null, null, null, $folder);
        xoops_template_clear_module_cache($xoopsModule->getVar('mid')); // Clear module's blocks cache

        foreach ($tpllist as $onetemplate) { // Remove cache for each page.
            if ('module' === $onetemplate->getVar('tpl_type')) {
                //  Note, I've been testing all the other methods (like the one of Smarty) and none of them run, that's why I have used this code
                $files_del = [];
                $files_del = glob(XOOPS_CACHE_PATH . '/*' . $onetemplate->getVar('tpl_file') . '*', GLOB_NOSORT);
                if (count($files_del) > 0 && is_array($files_del)) {
                    foreach ($files_del as $one_file) {
                        if (is_file($one_file)) {
                            unlink($one_file);
                        }
                    }
                }
            }
        }
    }

    /**
     * Redirect user with a message
     *
     * @param string $message message to display
     * @param string $url     The place where to go
     * @param mixed  $time
     */
    public static function redirect($message = '', $url = 'index.php', $time = 2)
    {
        redirect_header($url, $time, $message);
    }

    /**
     * Returns the module's name (as defined by the user in the module manager) with cache
     *
     * @return string Module's name
     */
    public static function getModuleName()
    {
        static $moduleName;
        if (!isset($moduleName)) {
            $mymodule   = self::_getModule();
            $moduleName = $mymodule->getVar('name');
        }

        return $moduleName;
    }

    /**
     * Create a title for the href tags inside html links
     *
     * @param string $title Text to use
     *
     * @return string Formated text
     */
    public static function makeHrefTitle($title)
    {
        $s = "\"'";
        $r = '  ';

        return strtr($title, $s, $r);
    }

    /**
     * Retourne la liste des utilisateurs appartenants à un groupe
     *
     * @param int $groupId Searched group
     *
     * @return array Array of XoopsUsers
     */
    public static function getUsersFromGroup($groupId)
    {
        $users         = [];
        $memberHandler = xoops_getHandler('member');
        $users         = $memberHandler->getUsersByGroup($groupId, true);

        return $users;
    }

    /**
     * Retourne la liste des emails des utilisateurs membres d'un groupe
     *
     * @param $groupId
     *
     * @return array Emails list
     * @internal param int $group_id Group's number
     */
    public static function getEmailsFromGroup($groupId)
    {
        $ret   = [];
        $users = self::getUsersFromGroup($groupId);
        foreach ($users as $user) {
            $ret[] = $user->getVar('email');
        }

        return $ret;
    }

    /**
     * Vérifie que l'utilisateur courant fait partie du groupe des administrateurs
     *
     * @return bool Admin or not
     */
    public static function isAdmin()
    {
        global $xoopsUser, $xoopsModule;
        if (is_object($xoopsUser)) {
            if (in_array(XOOPS_GROUP_ADMIN, $xoopsUser->getGroups())) {
                return true;
            } elseif (isset($xoopsModule) && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the current date in the Mysql format
     *
     * @return string Date in the Mysql format
     */
    public static function getCurrentSQLDate()
    {
        return date('Y-m-d'); // 2007-05-02
    }

    /**
     * @return bool|string
     */
    public static function getCurrentSQLDateTime()
    {
        return date('Y-m-d H:i:s'); // 2007-05-02
    }

    /**
     * Convert a Mysql date to the human's format
     *
     * @param string $date The date to convert
     * @param string $format
     *
     * @return string The date in a human form
     */
    public static function SQLDateToHuman($date, $format = 'l')
    {
        if ('0000-00-00' != $date && '' != xoops_trim($date)) {
            return formatTimestamp(strtotime($date), $format);
        }

        return '';
    }

    /**
     * Convert a timestamp to a Mysql date
     *
     * @param int $timestamp The timestamp to use
     *
     * @return string The date in the Mysql format
     */
    public static function timestampToMysqlDate($timestamp)
    {
        return date('Y-m-d', (int)$timestamp);
    }

    /**
     * Conversion d'un dateTime Mysql en date lisible en français
     *
     * @param $dateTime
     *
     * @return bool|string
     */
    public static function sqlDateTimeToFrench($dateTime)
    {
        return date('d/m/Y H:i:s', strtotime($dateTime));
    }

    /**
     * Convert a timestamp to a Mysql datetime form
     *
     * @param int $timestamp The timestamp to use
     *
     * @return string The date and time in the Mysql format
     */
    public static function timestampToMysqlDateTime($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * This function indicates if the current Xoops version needs to add asterisks to required fields in forms
     *
     * @return bool Yes = we need to add them, false = no
     */
    public static function needsAsterisk()
    {
        if (self::isX23()) {
            return false;
        }
        if (false !== mb_stripos(XOOPS_VERSION, 'impresscms')) {
            return false;
        }
        if (false === mb_stripos(XOOPS_VERSION, 'legacy')) {
            $xv = xoops_trim(str_replace('XOOPS ', '', XOOPS_VERSION));
            if ((int)mb_substr($xv, 4, 2) >= 17) {
                return false;
            }
        }

        return true;
    }

    /**
     * Mark the mandatory fields of a form with a star
     *
     * @param \XoopsObject $sform The form to modify
     *
     * @return \XoopsObject The modified form
     * @internal param string $caracter The character to use to mark fields
     */
    public static function &formMarkRequiredFields(XoopsObject $sform)
    {
        if (self::needsAsterisk()) {
            $required = [];
            foreach ($sform->getRequired() as $item) {
                $required[] = $item->_name;
            }
            $elements = [];
            $elements = $sform->getElements();
            foreach ($elements as $iValue) {
                if (is_object($iValue) && in_array($iValue->_name, $required)) {
                    $iValue->_caption .= ' *';
                }
            }
        }

        return $sform;
    }

    /**
     * Create an html heading (from h1 to h6)
     *
     * @param string $title The text to use
     * @param int    $level Level to return
     *
     * @return string The heading
     */
    public static function htitle($title = '', $level = 1)
    {
        printf('<h%01d>%s</h%01d>', $level, $title, $level);
    }

    /**
     * Create a unique upload filename
     *
     * @param string $folder   The folder where the file will be saved
     * @param string $fileName Original filename (coming from the user)
     * @param bool   $trimName Do we need to create a "short" unique name ?
     *
     * @return string The unique filename to use (with its extension)
     */
    public static function createUploadName($folder, $fileName, $trimName = false)
    {
        $workingfolder = $folder;
        if ('/' !== xoops_substr($workingfolder, mb_strlen($workingfolder) - 1, 1)) {
            $workingfolder .= '/';
        }
        $ext  = basename($fileName);
        $ext  = explode('.', $ext);
        $ext  = '.' . $ext[count($ext) - 1];
        $true = true;
        while ($true) {
            $ipbits = explode('.', $_SERVER['REMOTE_ADDR']);
            [$usec, $sec] = explode(' ', microtime());
            $usec *= 65536;
            $sec  = ((int)$sec) & 0xFFFF;

            if ($trimName) {
                $uid = sprintf('%06x%04x%04x', ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            } else {
                $uid = sprintf('%08x-%04x-%04x', ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            }
            if (!file_exists($workingfolder . $uid . $ext)) {
                $true = false;
            }
        }

        return $uid . $ext;
    }

    /**
     * Replace html entities with their ASCII equivalent
     *
     * @param string $chaine The string undecode
     *
     * @return string The undecoded string
     */
    public static function unhtml($chaine)
    {
        $search = $replace = [];
        $chaine = html_entity_decode($chaine);

        for ($i = 0; $i <= 255; ++$i) {
            $search[]  = '&#' . $i . ';';
            $replace[] = chr($i);
        }
        $replace[] = '...';
        $search[]  = '';
        $replace[] = "'";
        $search[]  = '';
        $replace[] = "'";
        $search[]  = '';
        $replace[] = '-';
        $search[]  = '&bull;'; // $replace[] = '';
        $replace[] = '';
        $search[]  = '&mdash;';
        $replace[] = '-';
        $search[]  = '&ndash;';
        $replace[] = '-';
        $search[]  = '&shy;';
        $replace[] = '"';
        $search[]  = '&quot;';
        $replace[] = '&';
        $search[]  = '&amp;';
        $replace[] = '';
        $search[]  = '&circ;';
        $replace[] = '¡';
        $search[]  = '&iexcl;';
        $replace[] = '¦';
        $search[]  = '&brvbar;';
        $replace[] = '¨';
        $search[]  = '&uml;';
        $replace[] = '¯';
        $search[]  = '&macr;';
        $replace[] = '´';
        $search[]  = '&acute;';
        $replace[] = '¸';
        $search[]  = '&cedil;';
        $replace[] = '¿';
        $search[]  = '&iquest;';
        $replace[] = '';
        $search[]  = '&tilde;';
        $replace[] = "'";
        $search[]  = '&lsquo;'; // $replace[]='';
        $replace[] = "'";
        $search[]  = '&rsquo;'; // $replace[]='';
        $replace[] = '';
        $search[]  = '&sbquo;';
        $replace[] = "'";
        $search[]  = '&ldquo;'; // $replace[]='';
        $replace[] = "'";
        $search[]  = '&rdquo;'; // $replace[]='';
        $replace[] = '';
        $search[]  = '&bdquo;';
        $replace[] = '';
        $search[]  = '&lsaquo;';
        $replace[] = '';
        $search[]  = '&rsaquo;';
        $replace[] = '<';
        $search[]  = '&lt;';
        $replace[] = '>';
        $search[]  = '&gt;';
        $replace[] = '±';
        $search[]  = '&plusmn;';
        $replace[] = '«';
        $search[]  = '&laquo;';
        $replace[] = '»';
        $search[]  = '&raquo;';
        $replace[] = '×';
        $search[]  = '&times;';
        $replace[] = '÷';
        $search[]  = '&divide;';
        $replace[] = '¢';
        $search[]  = '&cent;';
        $replace[] = '£';
        $search[]  = '&pound;';
        $replace[] = '¤';
        $search[]  = '&curren;';
        $replace[] = '¥';
        $search[]  = '&yen;';
        $replace[] = '§';
        $search[]  = '&sect;';
        $replace[] = '©';
        $search[]  = '&copy;';
        $replace[] = '¬';
        $search[]  = '&not;';
        $replace[] = '®';
        $search[]  = '&reg;';
        $replace[] = '°';
        $search[]  = '&deg;';
        $replace[] = 'µ';
        $search[]  = '&micro;';
        $replace[] = '¶';
        $search[]  = '&para;';
        $replace[] = '·';
        $search[]  = '&middot;';
        $replace[] = '';
        $search[]  = '&dagger;';
        $replace[] = '';
        $search[]  = '&Dagger;';
        $replace[] = '';
        $search[]  = '&permil;';
        $replace[] = 'Euro';
        $search[]  = '&euro;'; // $replace[]=''
        $replace[] = '¼';
        $search[]  = '&frac14;';
        $replace[] = '½';
        $search[]  = '&frac12;';
        $replace[] = '¾';
        $search[]  = '&frac34;';
        $replace[] = '¹';
        $search[]  = '&sup1;';
        $replace[] = '²';
        $search[]  = '&sup2;';
        $replace[] = '³';
        $search[]  = '&sup3;';
        $replace[] = 'á';
        $search[]  = '&aacute;';
        $replace[] = 'Á';
        $search[]  = '&Aacute;';
        $replace[] = 'â';
        $search[]  = '&acirc;';
        $replace[] = 'Â';
        $search[]  = '&Acirc;';
        $replace[] = 'à';
        $search[]  = '&agrave;';
        $replace[] = 'À';
        $search[]  = '&Agrave;';
        $replace[] = 'å';
        $search[]  = '&aring;';
        $replace[] = 'Å';
        $search[]  = '&Aring;';
        $replace[] = 'ã';
        $search[]  = '&atilde;';
        $replace[] = 'Ã';
        $search[]  = '&Atilde;';
        $replace[] = 'ä';
        $search[]  = '&auml;';
        $replace[] = 'Ä';
        $search[]  = '&Auml;';
        $replace[] = 'ª';
        $search[]  = '&ordf;';
        $replace[] = 'æ';
        $search[]  = '&aelig;';
        $replace[] = 'Æ';
        $search[]  = '&AElig;';
        $replace[] = 'ç';
        $search[]  = '&ccedil;';
        $replace[] = 'Ç';
        $search[]  = '&Ccedil;';
        $replace[] = 'ð';
        $search[]  = '&eth;';
        $replace[] = 'Ð';
        $search[]  = '&ETH;';
        $replace[] = 'é';
        $search[]  = '&eacute;';
        $replace[] = 'É';
        $search[]  = '&Eacute;';
        $replace[] = 'ê';
        $search[]  = '&ecirc;';
        $replace[] = 'Ê';
        $search[]  = '&Ecirc;';
        $replace[] = 'è';
        $search[]  = '&egrave;';
        $replace[] = 'È';
        $search[]  = '&Egrave;';
        $replace[] = 'ë';
        $search[]  = '&euml;';
        $replace[] = 'Ë';
        $search[]  = '&Euml;';
        $replace[] = '';
        $search[]  = '&fnof;';
        $replace[] = 'í';
        $search[]  = '&iacute;';
        $replace[] = 'Í';
        $search[]  = '&Iacute;';
        $replace[] = 'î';
        $search[]  = '&icirc;';
        $replace[] = 'Î';
        $search[]  = '&Icirc;';
        $replace[] = 'ì';
        $search[]  = '&igrave;';
        $replace[] = 'Ì';
        $search[]  = '&Igrave;';
        $replace[] = 'ï';
        $search[]  = '&iuml;';
        $replace[] = 'Ï';
        $search[]  = '&Iuml;';
        $replace[] = 'ñ';
        $search[]  = '&ntilde;';
        $replace[] = 'Ñ';
        $search[]  = '&Ntilde;';
        $replace[] = 'ó';
        $search[]  = '&oacute;';
        $replace[] = 'Ó';
        $search[]  = '&Oacute;';
        $replace[] = 'ô';
        $search[]  = '&ocirc;';
        $replace[] = 'Ô';
        $search[]  = '&Ocirc;';
        $replace[] = 'ò';
        $search[]  = '&ograve;';
        $replace[] = 'Ò';
        $search[]  = '&Ograve;';
        $replace[] = 'º';
        $search[]  = '&ordm;';
        $replace[] = 'ø';
        $search[]  = '&oslash;';
        $replace[] = 'Ø';
        $search[]  = '&Oslash;';
        $replace[] = 'õ';
        $search[]  = '&otilde;';
        $replace[] = 'Õ';
        $search[]  = '&Otilde;';
        $replace[] = 'ö';
        $search[]  = '&ouml;';
        $replace[] = 'Ö';
        $search[]  = '&Ouml;';
        $replace[] = '';
        $search[]  = '&oelig;';
        $replace[] = '';
        $search[]  = '&OElig;';
        $replace[] = '';
        $search[]  = '&scaron;';
        $replace[] = '';
        $search[]  = '&Scaron;';
        $replace[] = 'ß';
        $search[]  = '&szlig;';
        $replace[] = 'þ';
        $search[]  = '&thorn;';
        $replace[] = 'Þ';
        $search[]  = '&THORN;';
        $replace[] = 'ú';
        $search[]  = '&uacute;';
        $replace[] = 'Ú';
        $search[]  = '&Uacute;';
        $replace[] = 'û';
        $search[]  = '&ucirc;';
        $replace[] = 'Û';
        $search[]  = '&Ucirc;';
        $replace[] = 'ù';
        $search[]  = '&ugrave;';
        $replace[] = 'Ù';
        $search[]  = '&Ugrave;';
        $replace[] = 'ü';
        $search[]  = '&uuml;';
        $replace[] = 'Ü';
        $search[]  = '&Uuml;';
        $replace[] = 'ý';
        $search[]  = '&yacute;';
        $replace[] = 'Ý';
        $search[]  = '&Yacute;';
        $replace[] = 'ÿ';
        $search[]  = '&yuml;';
        $replace[] = '';
        $search[]  = '&Yuml;';
        $chaine    = str_replace($search, $replace, $chaine);

        return $chaine;
    }

    /**
     * Create a title to be used by the url rewriting
     *
     * @param string $content The text to use to create the url
     * @param int    $urw     The lower limit to create words
     *
     * @return string The text to use for the url
     *                Note, some parts are from Solo's code
     */
    public static function makeSeoUrl($content, $urw = 1)
    {
        $s       = "ÀÁÂÃÄÅÒÓÔÕÖØÈÉÊËÇÌÍÎÏÙÚÛÜÑàáâãäåòóôõöøèéêëçìíîïùúûüÿñ '()";
        $r       = 'AAAAAAOOOOOOEEEECIIIIUUUUYNaaaaaaooooooeeeeciiiiuuuuyn----';
        $content = self::unhtml($content); // First, remove html entities
        $content = strtr($content, $s, $r);
        $content = strip_tags($content);
        $content = mb_strtolower($content);
        $content = htmlentities($content, ENT_QUOTES | ENT_HTML5); // TODO: Vérifier
        $content = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/', '$1', $content);
        $content = html_entity_decode($content);
        $content = str_ireplace('quot', ' ', $content);
        $content = preg_replace("/'/i", ' ', $content);
        $content = str_ireplace('-', ' ', $content);
        $content = preg_replace('/[[:punct:]]/i', '', $content);

        // Selon option mais attention au fichier .htaccess !
        // $content = eregi_replace('[[:digit:]]','', $content);
        $content = preg_replace('/[^a-z|A-Z|0-9]/', '-', $content);

        $words    = explode(' ', $content);
        $keywords = '';
        foreach ($words as $word) {
            if (mb_strlen($word) >= $urw) {
                $keywords .= '-' . trim($word);
            }
        }
        if (!$keywords) {
            $keywords = '-';
        }
        // Supprime les tirets en double
        $keywords = str_replace('---', '-', $keywords);
        $keywords = str_replace('--', '-', $keywords);
        // Supprime un éventuel tiret à la fin de la chaine
        if ('-' === mb_substr($keywords, mb_strlen($keywords) - 1, 1)) {
            $keywords = mb_substr($keywords, 0, -1);
        }

        return $keywords;
    }

    /**
     * Create the meta keywords based on the content
     *
     * @param string $content Content from which we have to create metakeywords
     *
     * @return string The list of meta keywords
     */
    public static function createMetaKeywords($content)
    {
        $keywordscount = self::getModuleOption('metagen_maxwords');
        $keywordsorder = self::getModuleOption('metagen_order');

        $tmp = [];
        // Search for the "Minimum keyword length"
        if (Request::hasVar('xoopstube_keywords_limit', 'SESSION')) {
            $limit = $_SESSION['xoopstube_keywords_limit'];
        } else {
            $configHandler                        = xoops_getHandler('config');
            $xoopsConfigSearch                    = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);
            $limit                                = $xoopsConfigSearch['keyword_min'];
            $_SESSION['xoopstube_keywords_limit'] = $limit;
        }
        $myts            = MyTextSanitizer::getInstance();
        $content         = str_replace('<br>', ' ', $content);
        $content         = $myts->undoHtmlSpecialChars($content);
        $content         = strip_tags($content);
        $content         = mb_strtolower($content);
        $search_pattern  = ['&nbsp;', "\t", "\r\n", "\r", "\n", ',', '.', "'", ';', ':', ')', '(', '"', '?', '!', '{', '}', '[', ']', '<', '>', '/', '+', '-', '_', '\\', '*'];
        $replace_pattern = [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $content         = str_replace($search_pattern, $replace_pattern, $content);
        $keywords        = explode(' ', $content);
        switch ($keywordsorder) {
            case 0: // Ordre d'apparition dans le texte
                $keywords = array_unique($keywords);
                break;
            case 1: // Ordre de fréquence des mots
                $keywords = array_count_values($keywords);
                asort($keywords);
                $keywords = array_keys($keywords);
                break;
            case 2: // Ordre inverse de la fréquence des mots
                $keywords = array_count_values($keywords);
                arsort($keywords);
                $keywords = array_keys($keywords);
                break;
        }
        // Remove black listed words
        if ('' !== xoops_trim(self::getModuleOption('metagen_blacklist'))) {
            $metagen_blacklist = str_replace("\r", '', self::getModuleOption('metagen_blacklist'));
            $metablack         = explode("\n", $metagen_blacklist);
            array_walk($metablack, '\trim');
            $keywords = array_diff($keywords, $metablack);
        }

        foreach ($keywords as $keyword) {
            if (mb_strlen($keyword) >= $limit && !is_numeric($keyword)) {
                $tmp[] = $keyword;
            }
        }
        $tmp = array_slice($tmp, 0, $keywordscount);
        if (count($tmp) > 0) {
            return implode(',', $tmp);
        }
        if (!isset($configHandler) || !is_object($configHandler)) {
            /** @var \XoopsConfigHandler $configHandler */
            $configHandler = xoops_getHandler('config');
        }
        $xoopsConfigMetaFooter = $configHandler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
        return $xoopsConfigMetaFooter['meta_keywords'] ?? '';
    }

    /**
     * Fonction chargée de gérer l'upload
     *
     * @param int    $indice L'indice du fichier à télécharger
     * @param string $dstpath
     * @param null   $mimeTypes
     * @param null   $uploadMaxSize
     * @param null   $maxWidth
     * @param null   $maxHeight
     *
     * @return mixed True si l'upload s'est bien déroulé sinon le message d'erreur correspondant
     */
    public static function uploadFile(
        $indice,
        $dstpath = XOOPS_UPLOAD_PATH,
        $mimeTypes = null,
        $uploadMaxSize = null,
        $maxWidth = null,
        $maxHeight = null
    ) {
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        global $destname;
        if (Request::hasVar('xoops_upload_file', 'POST')) {
            require_once XOOPS_ROOT_PATH . '/class/uploader.php';
            $fldname = '';
            $fldname = $_FILES[$_POST['xoops_upload_file'][$indice]];
            $fldname = $fldname['name'];
            if (xoops_trim('' !== $fldname)) {
                $destname = self::createUploadName($dstpath, $fldname, true);
                if (null === $mimeTypes) {
                    $permittedtypes = explode("\n", str_replace("\r", '', self::getModuleOption('mimetypes')));
                    array_walk($permittedtypes, '\trim');
                } else {
                    $permittedtypes = $mimeTypes;
                }
                $uploadSize = $uploadMaxSize ?? self::getModuleOption('maxuploadsize');
                $uploader   = new Xoopstube\MediaUploader($dstpath, $permittedtypes, $uploadSize, $maxWidth, $maxHeight);
                //$uploader->allowUnknownTypes = true;
                $uploader->setTargetFileName($destname);
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][$indice])) {
                    if ($uploader->upload()) {
                        return true;
                    }

                    return _ERRORS . ' ' . htmlentities($uploader->getErrors(), ENT_QUOTES | ENT_HTML5);
                }

                return htmlentities($uploader->getErrors(), ENT_QUOTES | ENT_HTML5);
            }

            return false;
        }

        return false;
    }

    /**
     * Resize a Picture to some given dimensions (using the wideImage library)
     *
     * @param string $src_path      Picture's source
     * @param string $dst_path      Picture's destination
     * @param int    $param_width   Maximum picture's width
     * @param int    $param_height  Maximum picture's height
     * @param bool   $keep_original Do we have to keep the original picture ?
     * @param string $fit           Resize mode (see the wideImage library for more information)
     *
     * @return bool
     */
    public static function resizePicture(
        $src_path,
        $dst_path,
        $param_width,
        $param_height,
        $keep_original = false,
        $fit = 'inside'
    ) {
        $resize = true;
        if (XOOPSTUBE_DONT_RESIZE_IF_SMALLER) {
            $pictureDimensions = getimagesize($src_path);
            if (is_array($pictureDimensions)) {
                $width  = $pictureDimensions[0];
                $height = $pictureDimensions[1];
                if ($width < $param_width && $height < $param_height) {
                    $resize = false;
                }
            }
        }

        $img = WideImage::load($src_path);
        if ($resize) {
            $result = $img->resize($param_width, $param_height, $fit);
            $result->saveToFile($dst_path);
        } else {
            @copy($src_path, $dst_path);
        }

        if (!$keep_original) {
            @unlink($src_path);
        }

        return true;
    }

    /**
     * Add days to a date and return the new date in Mysql Date format
     *
     * @param int $duration
     * @param int $startingDate Starting date (timestamp)
     *
     * @return bool|string
     * @internal param int $ Duration in days
     */
    public static function addDaysToDate($duration = 1, $startingDate = 0)
    {
        if (0 == $startingDate) {
            $startingDate = time();
        }
        $endingDate = $startingDate + ($duration * 86400);

        return date('Y-m-d', $endingDate);
    }

    /**
     * Returns a breadcrumb according to the parameters passed and starting (automatically) from the root of the module
     *
     * @param array  $path  The full path (except the root) of the breadcrumb in the form of key = url value = title
     * @param string $raquo The default separator to use
     *
     * @return string le breadcrumb
     */
    public static function breadcrumb($path, $raquo = ' &raquo; ')
    {
        $breadcrumb        = '';
        $workingBreadcrumb = [];
        if (is_array($path)) {
            $moduleName          = self::getModuleName();
            $workingBreadcrumb[] = "<a href='" . XOOPSTUBE_URL . "' title='" . self::makeHrefTitle($moduleName) . "'>" . $moduleName . '</a>';
            foreach ($path as $url => $title) {
                $workingBreadcrumb[] = "<a href='" . $url . "'>" . $title . '</a>';
            }
            $cnt = count($workingBreadcrumb);
            foreach ($workingBreadcrumb as $i => $iValue) {
                if ($i == $cnt - 1) {
                    $workingBreadcrumb[$i] = strip_tags($iValue);
                }
            }
            $breadcrumb = implode($raquo, $workingBreadcrumb);
        }

        return $breadcrumb;
    }

    /**
     * @param $string
     *
     * @return string
     */
    public static function close_tags($string)
    {
        // match opened tags
        if (preg_match_all('/<([a-z\:\-]+)[^\/]>/', $string, $start_tags)) {
            $start_tags = $start_tags[1];

            // match closed tags
            if (preg_match_all('/<\/([a-z]+)>/', $string, $end_tags)) {
                $complete_tags = [];
                $end_tags      = $end_tags[1];

                foreach ($start_tags as $key => $val) {
                    $posb = array_search($val, $end_tags, true);
                    if (is_int($posb)) {
                        unset($end_tags[$posb]);
                    } else {
                        $complete_tags[] = $val;
                    }
                }
            } else {
                $complete_tags = $start_tags;
            }

            $complete_tags = array_reverse($complete_tags);
            foreach ($complete_tags as $iValue) {
                $string .= '</' . $iValue . '>';
            }
        }

        return $string;
    }

    /**
     * @param        $string
     * @param int    $length
     * @param string $etc
     * @param bool   $break_words
     *
     * @return mixed|string
     */
    public static function truncate_tagsafe($string, $length = 80, $etc = '...', $break_words = false)
    {
        if (0 == $length) {
            return '';
        }

        if (mb_strlen($string) > $length) {
            $length -= mb_strlen($etc);
            if (!$break_words) {
                $string = preg_replace('/\s+?(\S+)?$/', '', mb_substr($string, 0, $length + 1));
                $string = preg_replace('/<[^>]*$/', '', $string);
                $string = self::close_tags($string);
            }

            return $string . $etc;
        }

        return $string;
    }

    /**
     * Create an infotip
     * @param $text
     * @return string
     */
    public static function makeInfotips($text)
    {
        $ret      = '';
        $infotips = self::getModuleOption('infotips');
        if ($infotips > 0) {
            $myts = MyTextSanitizer::getInstance();
            $ret  = htmlspecialchars(xoops_substr(strip_tags($text), 0, $infotips));
        }

        return $ret;
    }

    /**
     * @param $datastream
     * @param $url
     *
     * @return string
     */
    public static function postIt($datastream, $url)
    {
        $url     = preg_replace('@^http://@i', '', $url);
        $host    = mb_substr($url, 0, mb_strpos($url, '/'));
        $uri     = mb_strstr($url, '/');
        $reqbody = '';
        foreach ($datastream as $key => $val) {
            if (!empty($reqbody)) {
                $reqbody .= '&';
            }
            $reqbody .= $key . '=' . urlencode($val);
        }
        $contentlength = mb_strlen($reqbody);
        $reqheader     = "POST $uri HTTP/1.1\r\n" . "Host: $host\n" . "Content-Type: application/x-www-form-urlencoded\r\n" . "Content-Length: $contentlength\r\n\r\n" . "$reqbody\r\n";

        return $reqheader;
    }

    /**
     * Returns the mime type of a file using first finfo then mime_content
     *
     * @param $filename
     * @return string
     */
    public static function getMimeType($filename)
    {
        if (function_exists('finfo_open')) {
            $finfo    = finfo_open();
            $mimetype = finfo_file($finfo, $filename, FILEINFO_MIME_TYPE);
            finfo_close($finfo);

            return $mimetype;
        }
        if (function_exists('mime_content_type')) {
            return mime_content_type($filename);
        }

        return '';
    }

    /**
     * Retourne un criteria compo qui permet de filtrer les produits sur le mois courant
     *
     * @return \CriteriaCompo
     */
    public static function getThisMonthCriteria()
    {
        $start             = mktime(0, 1, 0, date('n'), date('j'), date('Y'));
        $end               = mktime(0, 0, 0, date('n'), date('t'), date('Y'));
        $criteriaThisMonth = new CriteriaCompo();
        $criteriaThisMonth->add(new Criteria('product_submitted', $start, '>='));
        $criteriaThisMonth->add(new Criteria('product_submitted', $end, '<='));

        return $criteriaThisMonth;
    }

    /**
     * Retourne une liste d'objets XoopsUsers à partir d'une liste d'identifiants
     *
     * @param array $xoopsUsersIDs La liste des ID
     *
     * @return array Les objets XoopsUsers
     */
    public static function getUsersFromIds($xoopsUsersIDs)
    {
        $users = [];
        if ($xoopsUsersIDs && is_array($xoopsUsersIDs)) {
            $xoopsUsersIDs = array_unique($xoopsUsersIDs);
            sort($xoopsUsersIDs);
            if (count($xoopsUsersIDs) > 0) {
                /** @var \XoopsUserHandler $memberHandler */
                $memberHandler = xoops_getHandler('user');
                $criteria      = new Criteria('uid', '(' . implode(',', $xoopsUsersIDs) . ')', 'IN');
                $criteria->setSort('uid');
                $users = $memberHandler->getObjects($criteria, true);
            }
        }

        return $users;
    }

    /**
     * Retourne l'ID de l'utilisateur courant (s'il est connecté)
     *
     * @return int L'uid ou 0
     */
    public static function getCurrentUserID()
    {
        global $xoopsUser;
        $uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

        return $uid;
    }

    /**
     * Retourne la liste des groupes de l'utilisateur courant (avec cache)
     *
     * @param int $uid
     *
     * @return array Les ID des groupes auquel l'utilisateur courant appartient
     */
    public static function getMemberGroups($uid = 0)
    {
        static $buffer = [];
        if (0 == $uid) {
            $uid = self::getCurrentUserID();
        }

        if (is_array($buffer) && count($buffer) > 0 && isset($buffer[$uid])) {
            return $buffer[$uid];
        }
        if ($uid > 0) {
            $memberHandler = xoops_getHandler('member');
            $buffer[$uid]  = $memberHandler->getGroupsByUser($uid, false); // Renvoie un tableau d'ID (de groupes)
        } else {
            $buffer[$uid] = [XOOPS_GROUP_ANONYMOUS];
        }

        return $buffer[$uid];
    }

    /**
     * Indique si l'utilisateur courant fait partie d'une groupe donné (avec gestion de cache)
     *
     * @param int $group Groupe recherché
     * @param int $uid
     *
     * @return bool vrai si l'utilisateur fait partie du groupe, faux sinon
     */
    public static function isMemberOfGroup($group = 0, $uid = 0)
    {
        static $buffer = [];
        $retval = false;
        if (0 == $uid) {
            $uid = self::getCurrentUserID();
        }
        if (is_array($buffer) && array_key_exists($group, $buffer)) {
            $retval = $buffer[$group];
        } else {
            $memberHandler  = xoops_getHandler('member');
            $groups         = $memberHandler->getGroupsByUser($uid, false); // Renvoie un tableau d'ID (de groupes)
            $retval         = in_array($group, $groups);
            $buffer[$group] = $retval;
        }

        return $retval;
    }

    /**
     * Function responsible for verifying that a directory exists, we can write in and create an index.html file
     *
     * @param $folder
     */
    public static function prepareFolder($folder)
    {
        if (!is_dir($folder)) {
            if (!mkdir($folder) && !is_dir($folder)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $folder));
            }
            file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
        }
        //        chmod($folder, 0777);
    }

    /**
     * Duplicate a file in local
     *
     * @param string $path     The file's path
     * @param string $filename The filename
     *
     * @return mixed If the copy succeed, the new filename else false
     * @since 2.1
     */
    public static function duplicateFile($path, $filename)
    {
        $newName = self::createUploadName($path, $filename);
        if (copy($path . DIRECTORY_SEPARATOR . $filename, $path . DIRECTORY_SEPARATOR . $newName)) {
            return $newName;
        }

        return false;
    }

    //=================================================================================================================================

    /**
     * @param       $name
     * @param bool  $optional
     * @return bool
     */
    public static function getHandler($name, $optional = false)
    {
        global $handlers, $xoopsModule;

        $name = mb_strtolower(trim($name));
        if (!isset($handlers[$name])) {
            if (is_file($hnd_file = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/class/class_' . $name . '.php')) {
                require_once $hnd_file;
            }
            $class = 'xtube' . ucfirst($name) . 'Handler';
            if (class_exists($class)) {
                $handlers[$name] = new $class($GLOBALS['xoopsDB']);
            }
        }
        if (!isset($handlers[$name]) && !$optional) {
            trigger_error('<div>Class <span style="font-weight: bold;">' . $class . '</span> does not exist.</div><div>Handler Name: ' . $name, E_USER_ERROR) . '</div>';
        }

        return $handlers[$name] ?? false;
    }

    /**
     * @param int    $cid
     * @param string $permType
     * @param bool   $redirect
     *
     * @return bool
     */
    public static function checkGroups($cid = 0, $permType = 'XTubeCatPerm', $redirect = false)
    {
        global $xoopsModule;

        $groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = xoops_getHandler('groupperm');
        if (!$grouppermHandler->checkRight($permType, $cid, $groups, $xoopsModule->getVar('mid'))) {
            if (false === $redirect) {
                return false;
            }
            redirect_header('index.php', 3, _NOPERM);
        }

        return true;
    }

    /**
     * @param int $lid
     *
     * @return bool
     */
    public static function getVoteDetails($lid = 0)
    {
        $sql = 'SELECT
        COUNT(rating) AS rate,
        MIN(rating) AS min_rate,
        MAX(rating) AS max_rate,
        AVG(rating) AS avg_rate,
        COUNT(ratinguser) AS rating_user,
        MAX(ratinguser) AS max_user,
        MAX(title) AS max_title,
        MIN(title) AS min_title,
        sum(ratinguser = 0) AS null_ratinguser
            FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_votedata');
        if ($lid > 0) {
            $sql .= ' WHERE lid=' . $lid;
        }
        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            return false;
        }
        $ret = $GLOBALS['xoopsDB']->fetchArray($result);

        return $ret;
    }

    /**
     * @param int $sel_id
     *
     * @return array|bool
     */
    public static function calculateVoteData($sel_id = 0)
    {
        $ret                  = [];
        $ret['useravgrating'] = 0;

        $sql = 'SELECT rating FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_votedata');
        if (0 !== $sel_id) {
            $sql .= ' WHERE lid=' . $sel_id;
        }
        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            return false;
        }
        $ret['uservotes'] = $GLOBALS['xoopsDB']->getRowsNum($result);
        while (list($rating) = $GLOBALS['xoopsDB']->fetchRow($result)) {
            $ret['useravgrating'] += (int)$rating;
        }
        if ($ret['useravgrating'] > 0) {
            $ret['useravgrating'] = number_format($ret['useravgrating'] / $ret['uservotes'], 2);
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
    public static function cleanRequestVars(
        &$array,
        $name = null,
        $def = null,
        $strict = false,
        $lengthcheck = 15
    ) {
        // Sanitise $_request for further use.  This method gives more control and security.
        // Method is more for functionality rather than beauty at the moment, will correct later.
        unset($array['usercookie'], $array['PHPSESSID']);

        if (is_array($array) && null === $name) {
            $globals = [];
            foreach (array_keys($array) as $k) {
                $value = strip_tags(trim($array[$k]));
                if (mb_strlen($value >= $lengthcheck)) {
                    return null;
                }
                if (ctype_digit($value)) {
                    $value = (int)$value;
                } else {
                    if (true === $strict) {
                        $value = preg_replace('/\W/', '', trim($value));
                    }
                    $value = mb_strtolower((string)$value);
                }
                $globals[$k] = $value;
            }

            return $globals;
        }
        if (!isset($array[$name]) || !array_key_exists($name, $array)) {
            return $def;
        }
        $value = strip_tags(trim($array[$name]));

        if (ctype_digit($value)) {
            $value = (int)$value;
        } else {
            if (true === $strict) {
                $value = preg_replace('/\W/', '', trim($value));
            }
            $value = mb_strtolower((string)$value);
        }

        return $value;
    }

    /**
     * @param int $cid
     *
     * @return string
     */
    public static function renderToolbar($cid = 0)
    {
        $toolbar = '[ ';
        if (true === self::checkGroups($cid, 'XTubeSubPerm')) {
            $toolbar .= '<a href="submit.php?cid=' . $cid . '">' . _MD_XOOPSTUBE_SUBMITVIDEO . '</a> | ';
        }
        $toolbar .= '<a href="newlist.php?newvideoshowdays=7">' . _MD_XOOPSTUBE_LATESTLIST . '</a> | <a href="topten.php?list=hit">' . _MD_XOOPSTUBE_POPULARITY . '</a> | <a href="topten.php?list=rate">' . _MD_XOOPSTUBE_TOPRATED . '</a> ]';

        return $toolbar;
    }

    public static function getServerStatistics()
    {
        global $xoopsModule;
        echo '<fieldset style="border: #E8E8E8 1px solid;">
          <legend style="display: inline; font-weight: bold; color: #0A3760;">' . _AM_XOOPSTUBE_VIDEO_IMAGEINFO . '</legend>
          <div style="padding: 8px;">
            <img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/icon/server.png" alt="" style="float: left; padding-right: 10px;">
          <div>' . _AM_XOOPSTUBE_VIDEO_SPHPINI . '</div>';

        //    $safemode        = ini_get('safe_mode') ? _AM_XOOPSTUBE_VIDEO_ON . _AM_XOOPSTUBE_VIDEO_SAFEMODEPROBLEMS : _AM_XOOPSTUBE_VIDEO_OFF;
        //    $registerglobals = (ini_get('register_globals') === '') ? _AM_XOOPSTUBE_VIDEO_OFF : _AM_XOOPSTUBE_VIDEO_ON;
        $videos = ini_get('file_uploads') ? _AM_XOOPSTUBE_VIDEO_ON : _AM_XOOPSTUBE_VIDEO_OFF;

        $gdlib = function_exists('gd_info') ? _AM_XOOPSTUBE_VIDEO_GDON : _AM_XOOPSTUBE_VIDEO_GDOFF;
        echo '<li>' . _AM_XOOPSTUBE_VIDEO_GDLIBSTATUS . $gdlib;
        if (function_exists('gd_info')) {
            if (true === $gdlib = gd_info()) {
                echo '<li>' . _AM_XOOPSTUBE_VIDEO_GDLIBVERSION . '<b>' . $gdlib['GD Version'] . '</b>';
            }
        }
        echo '<br><br>';
        //    echo '<li>' . _AM_XOOPSTUBE_VIDEO_SAFEMODESTATUS . $safemode;
        //    echo '<li>' . _AM_XOOPSTUBE_VIDEO_REGISTERGLOBALS . $registerglobals;
        echo '<li>' . _AM_XOOPSTUBE_VIDEO_SERVERUPLOADSTATUS . $videos;
        echo '</div>';
        echo '</fieldset>';
    }

    // displayIcons()
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
    public static function displayIcons($time, $status = 0, $counter = 0)
    {
        global $xoopsModule;

        $new = '';
        $pop = '';

        $newdate = (time() - (86400 * (int)$GLOBALS['xoopsModuleConfig']['daysnew']));
        $popdate = (time() - (86400 * (int)$GLOBALS['xoopsModuleConfig']['daysupdated']));

        if (3 != $GLOBALS['xoopsModuleConfig']['displayicons']) {
            if ($newdate < $time) {
                if ((int)$status > 1) {
                    if (1 == $GLOBALS['xoopsModuleConfig']['displayicons']) {
                        $new = '&nbsp;<img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/icon/updated.gif" alt="" style="vertical-align: middle;">';
                    }
                    if (2 == $GLOBALS['xoopsModuleConfig']['displayicons']) {
                        $new = '<em>' . _MD_XOOPSTUBE_UPDATED . '</em>';
                    }
                } else {
                    if (1 == $GLOBALS['xoopsModuleConfig']['displayicons']) {
                        $new = '&nbsp;<img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/icon/new.gif" alt="" style="vertical-align: middle;">';
                    }
                    if (2 == $GLOBALS['xoopsModuleConfig']['displayicons']) {
                        $new = '<em>' . _MD_XOOPSTUBE_NEW . '</em>';
                    }
                }
            }
            if ($popdate > $time) {
                if ($counter >= $GLOBALS['xoopsModuleConfig']['popular']) {
                    if (1 == $GLOBALS['xoopsModuleConfig']['displayicons']) {
                        $pop = '&nbsp;<img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/icon/popular.png" alt="" style="vertical-align: middle;">';
                    }
                    if (2 == $GLOBALS['xoopsModuleConfig']['displayicons']) {
                        $pop = '<em>' . _MD_XOOPSTUBE_POPULAR . '!</em>';
                    }
                }
            }
        }
        $icons = $new . ' ' . $pop;

        return $icons;
    }

    // Reusable Link Sorting Functions
    // convertOrderByIn()
    // @param  $orderby
    // @return

    /**
     * @param $orderby
     *
     * @return string
     */
    public static function convertOrderByIn($orderby)
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

    /**
     * @param $orderby
     *
     * @return string
     */
    public static function convertOrderByTrans($orderby)
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

    /**
     * @param $orderby
     *
     * @return string
     */
    public static function convertOrderByOut($orderby)
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

    // updaterating()
    // @param  $sel_id
    // @return updates rating data in itemtable for a given item

    /**
     * @param $sel_id
     */
    public static function updateRating($sel_id)
    {
        $query       = 'SELECT rating FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_votedata') . ' WHERE lid=' . $sel_id;
        $voteresult  = $GLOBALS['xoopsDB']->query($query);
        $votesDB     = $GLOBALS['xoopsDB']->getRowsNum($voteresult);
        $totalrating = 0;
        while (list($rating) = $GLOBALS['xoopsDB']->fetchRow($voteresult)) {
            $totalrating += $rating;
        }
        $finalrating = $totalrating / $votesDB;
        $finalrating = number_format($finalrating, 4);
        $sql         = sprintf('UPDATE `%s` SET rating = %u, votes = %u WHERE lid = %u', $GLOBALS['xoopsDB']->prefix('xoopstube_videos'), $finalrating, $votesDB, $sel_id);
        $GLOBALS['xoopsDB']->query($sql);
    }

    // totalcategory()
    // @param integer $pid
    // @return

    /**
     * @param int $pid
     *
     * @return int
     */
    public static function getTotalCategoryCount($pid = 0)
    {
        $sql = 'SELECT cid FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat');
        if ($pid > 0) {
            $sql .= ' WHERE pid = 0';
        }
        $result     = $GLOBALS['xoopsDB']->query($sql);
        $catlisting = 0;
        while (list($cid) = $GLOBALS['xoopsDB']->fetchRow($result)) {
            if (self::checkGroups($cid)) {
                ++$catlisting;
            }
        }

        return $catlisting;
    }

    // getTotalItems()
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
    public static function getTotalItems($sel_id = 0, $get_child = 0, $return_sql = 0)
    {
        global $mytree, $_check_array;

        if ($sel_id > 0) {
            $sql = 'SELECT a.lid, a.cid, a.published FROM '
                   . $GLOBALS['xoopsDB']->prefix('xoopstube_videos')
                   . ' a LEFT JOIN '
                   . $GLOBALS['xoopsDB']->prefix('xoopstube_altcat')
                   . ' b'
                   . ' ON b.lid=a.lid'
                   . ' WHERE a.published > 0 AND a.published <= '
                   . time()
                   . ' AND (a.expired = 0 OR a.expired > '
                   . time()
                   . ') AND offline = 0 '
                   . ' AND (b.cid=a.cid OR (a.cid='
                   . $sel_id
                   . ' OR b.cid='
                   . $sel_id
                   . '))'
                   . ' GROUP BY a.lid, a.cid, a.published';
        } else {
            $sql = 'SELECT lid, cid, published FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' WHERE offline = 0 AND published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time() . ')';
        }
        if (1 == $return_sql) {
            return $sql;
        }

        $count          = 0;
        $published_date = 0;

        $arr    = [];
        $result = $GLOBALS['xoopsDB']->query($sql);
        while (list($lid, $cid, $published) = $GLOBALS['xoopsDB']->fetchRow($result)) {
            if (true === self::checkGroups()) {
                ++$count;
                $published_date = ($published > $published_date) ? $published : $published_date;
            }
        }

        $child_count = 0;
        if (1 == $get_child) {
            $arr  = $mytree->getAllChildId($sel_id);
            $size = count($arr);
            foreach ($arr as $iValue) {
                $query2 = 'SELECT a.lid, a.published, a.cid FROM '
                          . $GLOBALS['xoopsDB']->prefix('xoopstube_videos')
                          . ' a LEFT JOIN '
                          . $GLOBALS['xoopsDB']->prefix('xoopstube_altcat')
                          . ' b'
                          . ' ON b.lid = a.lid'
                          . ' WHERE a.published > 0 AND a.published <= '
                          . time()
                          . ' AND (a.expired = 0 OR a.expired > '
                          . time()
                          . ') AND offline = 0'
                          . ' AND (b.cid=a.cid OR (a.cid='
                          . $iValue
                          . ' OR b.cid='
                          . $iValue
                          . ')) GROUP BY a.lid, a.published, a.cid';

                $result2 = $GLOBALS['xoopsDB']->query($query2);
                while (list($lid, $published) = $GLOBALS['xoopsDB']->fetchRow($result2)) {
                    if (0 == $published) {
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
    public static function renderImageHeader($indeximage = '', $indexheading = '')
    {
        if ('' === $indeximage) {
            $result = $GLOBALS['xoopsDB']->query('SELECT indeximage, indexheading FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_indexpage'));
            [$indeximage, $indexheading] = $GLOBALS['xoopsDB']->fetchrow($result);
        }

        $image = '';
        if (!empty($indeximage)) {
            $image = self::displayImage($indeximage, 'index.php', $GLOBALS['xoopsModuleConfig']['mainimagedir'], $indexheading);
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
    public static function displayImage($image = '', $path = '', $imgsource = '', $alttext = '')
    {
        global $xoopsModule;

        $showimage = '';
        // Check to see if link is given
        if ($path) {
            $showimage = '<a href="' . $path . '">';
        }
        // checks to see if the file is valid else displays default blank image
        if (is_file(XOOPS_ROOT_PATH . "/{$imgsource}/{$image}")//            && is_dir(XOOPS_ROOT_PATH . "/{$imgsource}/{$image}")
        ) {
            $showimage .= "<img src='" . XOOPS_URL . "/{$imgsource}/{$image}' border='0' title='" . $alttext . "' alt='" . $alttext . "'></a>";
        } elseif ($GLOBALS['xoopsUser'] && $GLOBALS['xoopsUser']->isAdmin($xoopsModule->getVar('mid'))) {
            $showimage .= '<img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/brokenimg.png" alt="' . _MD_XOOPSTUBE_ISADMINNOTICE . '"></a>';
        } else {
            $showimage .= '<img src="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/assets/images/blank.png" alt="' . $alttext . '"></a>';
        }
        clearstatcache();

        return $showimage;
    }

    /**
     * @param $published
     *
     * @return mixed
     */
    public static function isNewImage($published)
    {
        global $xoopsModule;

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
    public static function findStringChar($haystack, $needle)
    {
        return mb_substr($haystack, 0, mb_strpos($haystack, $needle) + 1);
    }

    /**
     * @param string $header
     * @param string $menu
     * @param string $extra
     * @param int    $scount
     *
     * @return bool|null
     */
    public static function renderAdminMenu($header = '', $menu = '', $extra = '', $scount = 4)
    {
        global $xoopsModule;

        $_named_vidid = xoops_getenv('SCRIPT_NAME');
        if ($_named_vidid) {
            $thispage = basename($_named_vidid);
        }

        //    $op = (isset($_GET['op'])) ? $op = '?op=' . $_GET['op'] : '';
        $op = Request::getString('op', '', 'GET');
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
        </div><br>';

        if (empty($menu)) {
            // You can change this part to suit your own module. Defining this here will save you form having to do this each time.
            $menu = [
                _AM_XOOPSTUBE_MVIDEOS   => 'main.php?op=edit',
                _AM_XOOPSTUBE_MCATEGORY => 'category.php',
                _AM_XOOPSTUBE_INDEXPAGE => 'indexpage.php',
                //            _AM_XOOPSTUBE_MXOOPSTUBE     => 'main.php?op=edit',
                _AM_XOOPSTUBE_MUPLOADS  => 'upload.php',
                _AM_XOOPSTUBE_VUPLOADS  => 'vupload.php',
                _AM_XOOPSTUBE_MVOTEDATA => 'votedata.php',
                _AM_XOOPSTUBE_MCOMMENTS => '../../system/admin.php?module=' . $xoopsModule->getVar('mid') . '&status=0&limit=100&fct=comments&selsubmit=Go',
            ];
        }

        if (!is_array($menu)) {
            echo '<table width="100%" cellpadding="2" cellspacing="1" class="outer">';
            echo '<tr><td class="even" align="center"><b>' . _AM_XOOPSTUBE_NOMENUITEMS . '</b></td></tr></table><br>';

            return false;
        }

        $oddnum = [
            1  => '1',
            3  => '3',
            5  => '5',
            7  => '7',
            9  => '9',
            11 => '11',
            13 => '13',
        ];
        // number of rows per menu
        $menurows = count($menu) / $scount;
        // total amount of rows to complete menu
        $menurow = ceil($menurows) * $scount;
        // actual number of menuitems per row
        $rowcount = $menurow / ceil($menurows);
        $count    = 0;
        for ($i = count($menu); $i < $menurow; ++$i) {
            $tempArray = [1 => null];
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
                    if ('odd' === $classcol[$i - 1]) {
                        $classcol[$i] = ('odd' === $classcol[$i - 1] && in_array($classcounts, $oddnum)) ? 'even' : 'odd';
                    } else {
                        $classcol[$i] = ('even' === $classcol[$i - 1] && in_array($classcounts, $oddnum)) ? 'odd' : 'even';
                    }
                    $classcounts = 0;
                } else {
                    $classcol[$i] = ('even' === $classcol[$i - 1]) ? 'odd' : 'even';
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
            echo '</table><br>';
            unset($count, $menucount);
        }
        // ###### Output warn messages for security ######
        if (is_dir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/update/')) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL1, XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/update/'));
            echo '<br>';
        }

        $_file = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/update.php';
        if (file_exists($_file)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL2, XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/update.php'));
            echo '<br>';
        }

        $path1 = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['mainimagedir'];
        if (!is_dir($path1)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path1));
            echo '<br>';
        }
        if (!is_writable($path1)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path1));
            echo '<br>';
        }

        $path1_t = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['mainimagedir'] . '/thumbs';
        if (!is_dir($path1_t)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path1_t));
            echo '<br>';
        }
        if (!is_writable($path1_t)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path1_t));
            echo '<br>';
        }

        $path2 = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['videoimgdir'];
        if (!is_dir($path2)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path2));
            echo '<br>';
        }
        if (!is_writable($path2)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path2));
            echo '<br>';
        }

        //    $path2_t = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['videoimgdir'] . '/thumbs';
        //    if ( !is_dir( $path2_t ) || !is_writable( $path2_t ) ) {
        //        xoops_error( sprintf( _AM_XOOPSTUBE_WARNINSTALL3, $path2_t ) );
        //        echo '<br>';
        //    }

        $path3 = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['catimage'];
        if (!is_dir($path3)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path3));
            echo '<br>';
        }
        if (!is_writable($path3)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path3));
            echo '<br>';
        }

        $path3_t = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['catimage'] . '/thumbs';
        if (!is_dir($path3_t)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path3_t));
            echo '<br>';
        }
        if (!is_writable($path3_t)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path3_t));
            echo '<br>';
        }

        $path4 = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['videodir'];
        if (!is_dir($path4)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL3, $path4));
            echo '<br>';
        }
        if (!is_writable($path4)) {
            xoops_error(sprintf(_AM_XOOPSTUBE_WARNINSTALL4, $path4));
            echo '<br>';
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
    public static function getDirSelectOption($selected, $dirarray, $namearray)
    {
        echo "<select size='1' name='workd' onchange='location.href=\"upload.php?rootpath=\"+this.options[this.selectedIndex].value'>";
        echo "<option value=''>--------------------------------------</option>";
        foreach ($namearray as $namearray => $workd) {
            $opt_selected = '';
            if ($workd == $selected) {
                $opt_selected = 'selected';
            }
            echo '<option value="' . htmlspecialchars($namearray, ENT_QUOTES) . '" $opt_selected>' . $workd . '</option>';
        }
        echo '</select>';
    }

    //    /**
    //     * @param $selected
    //     * @param $dirarray
    //     * @param $namearray
    //     */
    //    public static function getDirSelectOption($selected, $dirarray, $namearray)
    //    {
    //        echo "<select size='1' name='workd' onchange='location.href=\"vupload.php?rootpath=\"+this.options[this.selectedIndex].value'>";
    //        echo "<option value=''>--------------------------------------</option>";
    //        foreach ($namearray as $namearray => $workd) {
    //            $opt_selected = '';
    //            if ($workd == $selected) {
    //                $opt_selected = 'selected';
    //            }
    //            echo '<option value="' . htmlspecialchars($namearray, ENT_QUOTES) . '" $opt_selected>' . $workd . '</option>';
    //        }
    //        echo '</select>';
    //    }

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
    public static function uploadFiles(
        $FILES,
        $uploaddir = 'uploads',
        $allowed_mimetypes = '',
        $redirecturl = 'index.php', //    $num = 0,
        $redirect = 0,
        $usertype = 1
    ) {
        global $FILES, $xoopsModule;

        $down = [];
        //       require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/class/uploader.php';
        //        require_once __DIR__ . '/uploader.php';
        //        require XOOPS_ROOT_PATH . '/class/uploader.php';

        if (empty($allowed_mimetypes)) {
            $allowed_mimetypes = xtube_getmime($FILES['userfile']['name'], $usertype);
        }
        $upload_dir = XOOPS_ROOT_PATH . '/' . $uploaddir . '/';

        $maxfilesize = $GLOBALS['xoopsModuleConfig']['maxfilesize'];

        $maxfilewidth  = $GLOBALS['xoopsModuleConfig']['maximgwidth'];
        $maxfileheight = $GLOBALS['xoopsModuleConfig']['maximgheight'];

        $uploader = new Xoopstube\MediaUploader($upload_dir, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        $uploader->noAdminSizeCheck(1);
        //if ($uploader->fetchMedia(Request::getArray('xoops_upload_file[0]', array(), 'POST'))) {
        if ($uploader->fetchMedia(Request::getArray('xoops_upload_file', '', 'POST')[0])) {
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header($redirecturl, 2, $errors);
            } elseif ($redirect) {
                redirect_header($redirecturl, 1, _AM_XOOPSTUBE_UPLOADFILE);
            } else {
                if (is_file($uploader->savedDestination)) {
                    $down['url']  = XOOPS_URL . '/' . $uploaddir . '/' . mb_strtolower($uploader->savedFileName);
                    $down['size'] = filesize(XOOPS_ROOT_PATH . '/' . $uploaddir . '/' . mb_strtolower($uploader->savedFileName));
                }

                return $down;
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
    public static function renderCategoryListHeader($heading)
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
<!--            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_PUBLISH . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_EXPIRE . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_ONLINE . '</th>
            <th style="text-align: center; font-size: smaller;">' . _AM_XOOPSTUBE_MINDEX_ACTION . '</th> -->
        </tr>
        ';
    }

    /**
     * @param $published
     */
    public static function renderCategoryListBody($published)
    {
        global $xtubemyts, $xtubeImageArray;

        $lid = $published['lid'];
        $cid = $published['cid'];

        $title        = '<a href="../singlevideo.php?cid=' . $published['cid'] . '&amp;lid=' . $published['lid'] . '">' . htmlspecialcharsStrip(trim($published['title'])) . '</a>';
        $maintitle    = urlencode(htmlspecialchars(trim($published['title'])));
        $cattitle     = '<a href="../viewcat.php?cid=' . $published['cid'] . '">' . self::getCategoryTitle($published['cid']) . '</a>';
        $submitter    = self::getLinkedUserNameFromId($published['submitter']);
        $returnsource = xtubeReturnSource($published['vidsource']);
        $submitted    = self::getTimestamp(formatTimestamp($published['date'], $GLOBALS['xoopsModuleConfig']['dateformatadmin']));
        $publish      = ($published['published'] > 0) ? self::getTimestamp(formatTimestamp($published['published'], $GLOBALS['xoopsModuleConfig']['dateformatadmin'])) : 'Not Published';
        $expires      = $published['expired'] ? self::getTimestamp(formatTimestamp($published['expired'], $GLOBALS['xoopsModuleConfig']['dateformatadmin'])) : _AM_XOOPSTUBE_MINDEX_NOTSET;

        if ((($published['expired'] && $published['expired'] > time()) || 0 == $published['expired'])
            && ($published['published'] && $published['published'] < time())
            && 0 == $published['offline']) {
            $published_status = $xtubeImageArray['online'];
        } elseif (($published['expired'] && $published['expired'] < time()) && 0 == $published['offline']) {
            $published_status = $xtubeImageArray['expired'];
        } else {
            $published_status = (0 == $published['published']) ? '<a href="newvideos.php">' . $xtubeImageArray['offline'] . '</a>' : $xtubeImageArray['offline'];
        }

        if (200 == $published['vidsource']) {
            $icon = '<a href="main.php?op=edit&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_EDIT . '">' . $xtubeImageArray['editimg'] . '</a>&nbsp;';
        } else {
            $icon = '<a href="main.php?op=edit&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_EDIT . '">' . $xtubeImageArray['editimg'] . '</a>&nbsp;';
        }
        $icon .= '<a href="main.php?op=delete&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_DELETE . '">' . $xtubeImageArray['deleteimg'] . '</a>&nbsp;';
        $icon .= '<a href="altcat.php?op=main&amp;cid=' . $cid . '&amp;lid=' . $lid . '&amp;title=' . $published['title'] . '" title="' . _AM_XOOPSTUBE_ALTCAT_CREATEF . '">' . $xtubeImageArray['altcat'] . '</a>';

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
        //        unset($published);
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
    public static function setPageNavigationCategoryList(
        $pubrowamount,
        $start,
        $art,
        $_this,
        $align
    ) {
        if ($pubrowamount < $GLOBALS['xoopsModuleConfig']['admin_perpage']) {
            return false;
        }
        // Display Page Nav if published is > total display pages amount.
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new XoopsPageNav($pubrowamount, $GLOBALS['xoopsModuleConfig']['admin_perpage'], $start, 'st' . $art, $_this);
        echo '<div style="text-align: ' . $align . '; padding: 8px;">' . $pagenav->renderNav() . '</div>';

        return null;
    }

    public static function renderCategoryListFooter()
    {
        echo '<tr style="text-align: center;">
            <td class="head" colspan="7">' . _AM_XOOPSTUBE_MINDEX_NOVIDEOSFOUND . '</td>
          </tr>';
    }

    /**
     * @param $heading
     */
    public static function renderVideoListHeader($heading)
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
    public static function renderVideoListBody($published)
    {
        global $xtubemyts, $xtubeImageArray, $pathIcon16;

        $lid = $published['lid'];
        $cid = $published['cid'];

        $title        = '<a href="../singlevideo.php?cid=' . $published['cid'] . '&amp;lid=' . $published['lid'] . '">' . htmlspecialchars(trim($published['title'])) . '</a>';
        $maintitle    = urlencode(htmlspecialchars(trim($published['title'])));
        $cattitle     = '<a href="../viewcat.php?cid=' . $published['cid'] . '">' . self::getCategoryTitle($published['cid']) . '</a>';
        $submitter    = self::getLinkedUserNameFromId($published['submitter']);
        $returnsource = xtubeReturnSource($published['vidsource']);
        $submitted    = self::getTimestamp(formatTimestamp($published['date'], $GLOBALS['xoopsModuleConfig']['dateformatadmin']));
        $publish      = ($published['published'] > 0) ? self::getTimestamp(formatTimestamp($published['published'], $GLOBALS['xoopsModuleConfig']['dateformatadmin'])) : 'Not Published';
        $expires      = $published['expired'] ? self::getTimestamp(formatTimestamp($published['expired'], $GLOBALS['xoopsModuleConfig']['dateformatadmin'])) : _AM_XOOPSTUBE_MINDEX_NOTSET;

        if ((($published['expired'] && (int)$published['expired'] > time()) || 0 === (int)$published['expired'])
            && ($published['published'] && (int)$published['published'] < time())
            && 0 === (int)$published['offline']) {
            $published_status = '<a href="main.php?op=toggle&amp;lid=' . $lid . '&amp;offline=' . (int)$published['offline'] . '"><img src="' . $pathIcon16 . '/1.png' . '"></a>';
        } elseif (($published['expired'] && (int)$published['expired'] < time()) && 0 === (int)$published['offline']) {
            $published_status = $xtubeImageArray['expired'];
        } else {
            $published_status = (0 === (int)$published['published']) ? '<a href="newvideos.php">' . $xtubeImageArray['offline'] . '</a>' : '<a href="main.php?op=toggle&amp;lid=' . $lid . '&amp;offline=' . $published['offline'] . '"><img src="' . $pathIcon16 . '/0.png' . '"></a>';
        }

        if (200 == $published['vidsource']) {
            $icon = '<a href="main.php?op=edit&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_EDIT . '">' . $xtubeImageArray['editimg'] . '</a>&nbsp;';
        } else {
            $icon = '<a href="main.php?op=edit&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_EDIT . '">' . $xtubeImageArray['editimg'] . '</a>&nbsp;';
        }
        $icon .= '<a href="main.php?op=delete&amp;lid=' . $lid . '" title="' . _AM_XOOPSTUBE_ICO_DELETE . '">' . $xtubeImageArray['deleteimg'] . '</a>&nbsp;';
        $icon .= '<a href="altcat.php?op=main&amp;cid=' . $cid . '&amp;lid=' . $lid . '&amp;title=' . $published['title'] . '" title="' . _AM_XOOPSTUBE_ALTCAT_CREATEF . '">' . $xtubeImageArray['altcat'] . '</a>';

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
        //        unset($published);
    }

    /**
     * @param $catt
     *
     * @return mixed
     */
    public static function getCategoryTitle($catt)
    {
        $sql    = 'SELECT title FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_cat') . ' WHERE cid=' . $catt;
        $result = $GLOBALS['xoopsDB']->query($sql);
        $result = $GLOBALS['xoopsDB']->fetchArray($result);

        return $result['title'];
    }

    public static function renderVideoListFooter()
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
    public static function setPageNavigationVideoList($pubrowamount, $start, $art, $_this, $align)
    {
        if ($pubrowamount < $GLOBALS['xoopsModuleConfig']['admin_perpage']) {
            return false;
        }
        // Display Page Nav if published is > total display pages amount.
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new XoopsPageNav($pubrowamount, $GLOBALS['xoopsModuleConfig']['admin_perpage'], $start, 'st' . $art, $_this);
        echo '<div style="text-align: ' . $align . '; padding: 8px;">' . $pagenav->renderNav() . '</div>';

        return null;
    }

    /**
     * @param $document
     *
     * @return mixed
     */
    public static function convertHtml2text($document)
    {
        // PHP Manual:: function preg_replace
        // $document should contain an HTML document.
        // This will remove HTML tags, javascript sections
        // and white space. It will also convert some
        // common HTML entities to their text equivalent.
        // Credits : newbb2
        $search = [
            "'<script[^>]*?>.*?</script>'si", // Strip out javascript
            "'<img.*?>'si", // Strip out img tags
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
        ]; // evaluate as php

        $replace = [
            '',
            '',
            '',
            '\\1',
            '"',
            '&',
            '<',
            '>',
            ' ',
            chr(161),
            chr(162),
            chr(163),
            chr(169),
        ];

        $text = preg_replace($search, $replace, $document);

        preg_replace_callback(
            '/&#(\d+);/',
            static function ($matches) {
                return chr($matches[1]);
            },
            $document
        );

        return $text;
    }

    // Check if Tag module is installed

    /**
     * @return bool
     */
    public static function isModuleTagInstalled()
    {
        static $isModuleTagInstalled;
        if (!isset($isModuleTagInstalled)) {
            /** @var \XoopsModuleHandler $moduleHandler */
            $moduleHandler = xoops_getHandler('module');
            $tag_mod       = $moduleHandler->getByDirname('tag');
            if (!$tag_mod) {
                $tag_mod = false;
            } else {
                $isModuleTagInstalled = 1 == $tag_mod->getVar('isactive');
            }
        }

        return $isModuleTagInstalled;
    }

    // Add item_tag to Tag-module

    /**
     * @param $lid
     * @param $item_tag
     */
    public static function updateTag($lid, $item_tag)
    {
        global $xoopsModule;
        if (self::isModuleTagInstalled()) {
            require_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
            $tagHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Tag'); // xoops_getModuleHandler('tag', 'tag');
            $tagHandler->updateByItem($item_tag, $lid, $xoopsModule->getVar('dirname'), 0);
        }
    }

    /**
     * @param $lid
     */
    public static function updateCounter($lid)
    {
        $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos') . ' SET hits=hits+1 WHERE lid=' . (int)$lid;
        $result = $GLOBALS['xoopsDB']->queryF($sql);
    }

    /**
     * @param $banner_id
     *
     * @return null|string
     */
    public static function getBannerFromBannerId($banner_id)
    {
        ###### Hack by www.stefanosilvestrini.com ######
        $db      = XoopsDatabaseFactory::getDatabaseConnection();
        $bresult = $db->query('SELECT COUNT(*) FROM ' . $db->prefix('banner') . ' WHERE bid=' . $banner_id);
        [$numrows] = $db->fetchRow($bresult);
        if ($numrows > 1) {
            --$numrows;
            $bannum = mt_rand(0, $numrows);
        } else {
            $bannum = 0;
        }
        if ($numrows > 0) {
            $bresult = $db->query('SELECT * FROM ' . $db->prefix('banner') . ' WHERE bid=' . $banner_id, 1, $bannum);
            [$bid, $cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date, $htmlbanner, $htmlcode] = $db->fetchRow($bresult);
            if ($GLOBALS['xoopsConfig']['my_ip'] == xoops_getenv('REMOTE_ADDR')) {
                // EMPTY
            } else {
                $db->queryF(sprintf('UPDATE `%s` SET impmade = impmade+1 WHERE bid = %u', $db->prefix('banner'), $bid));
            }
            /* Check if this impression is the last one and print the banner */
            if ($imptotal == $impmade) {
                $newid = $db->genId($db->prefix('bannerfinish') . '_bid_seq');
                $sql   = sprintf('INSERT INTO `%s` (bid, cid, impressions, clicks, datestart, dateend) VALUES (%u, %u, %u, %u, %u, %u)', $db->prefix('bannerfinish'), $newid, $cid, $impmade, $clicks, $date, time());
                $db->queryF($sql);
                $db->queryF(sprintf('DELETE FROM `%s` WHERE bid = %u', $db->prefix('banner'), $bid));
            }
            if ($htmlbanner) {
                $bannerobject = $htmlcode;
            } else {
                $bannerobject = '<div align="center"><a href="' . XOOPS_URL . '/banners.php?op=click&bid=' . $bid . '" target="_blank">';
                if (false !== mb_stripos($imageurl, '.swf')) {
                    $bannerobject .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" width="468" height="60">'
                                     . '<param name="movie" value="'
                                     . $imageurl
                                     . '"></param>'
                                     . '<param name="quality" value="high"></param>'
                                     . '<embed src="'
                                     . $imageurl
                                     . '" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="468" height="60">'
                                     . '</embed>'
                                     . '</object>';
                } else {
                    $bannerobject .= '<img src="' . $imageurl . '" alt="">';
                }
                $bannerobject .= '</a></div>';
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
    public static function getBannerFromClientId($client_id)
    {
        ###### Hack by www.stefanosilvestrini.com ######
        $db      = XoopsDatabaseFactory::getDatabaseConnection();
        $bresult = $db->query('SELECT COUNT(*) FROM ' . $db->prefix('banner') . ' WHERE cid=' . $client_id);
        [$numrows] = $db->fetchRow($bresult);
        if ($numrows > 1) {
            --$numrows;
            $bannum = mt_rand(0, $numrows);
        } else {
            $bannum = 0;
        }
        if ($numrows > 0) {
            $bresult = $db->query('SELECT * FROM ' . $db->prefix('banner') . ' WHERE cid=' . $client_id . ' ORDER BY rand()', 1, $bannum);
            [$bid, $cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date, $htmlbanner, $htmlcode] = $db->fetchRow($bresult);
            if ($GLOBALS['xoopsConfig']['my_ip'] == xoops_getenv('REMOTE_ADDR')) {
                // EMPTY
            } else {
                $db->queryF(sprintf('UPDATE `%s` SET impmade = impmade+1 WHERE bid = %u', $db->prefix('banner'), $bid));
            }
            /* Check if this impression is the last one and print the banner */
            if ($imptotal == $impmade) {
                $newid = $db->genId($db->prefix('bannerfinish') . '_bid_seq');
                $sql   = sprintf('INSERT INTO `%s` (bid, cid, impressions, clicks, datestart, dateend) VALUES (%u, %u, %u, %u, %u, %u)', $db->prefix('bannerfinish'), $newid, $cid, $impmade, $clicks, $date, time());
                $db->queryF($sql);
                $db->queryF(sprintf('DELETE FROM `%s` WHERE bid = %u', $db->prefix('banner'), $bid));
            }
            if ($htmlbanner) {
                $bannerobject = $htmlcode;
            } else {
                $bannerobject = '<div align="center"><a href="' . XOOPS_URL . '/banners.php?op=click&bid=' . $bid . '" target="_blank">';
                if (false !== mb_stripos($imageurl, '.swf')) {
                    $bannerobject .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" width="468" height="60">'
                                     . '<param name="movie" value="'
                                     . $imageurl
                                     . '"></param>'
                                     . '<param name="quality" value="high"></param>'
                                     . '<embed src="'
                                     . $imageurl
                                     . '" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="468" height="60">'
                                     . '</embed>'
                                     . '</object>';
                } else {
                    $bannerobject .= '<img src="' . $imageurl . '" alt="">';
                }
                $bannerobject .= '</a></div>';
            }

            return $bannerobject;
        }

        return null;
    }

    public static function setNoIndexNoFollow()
    {
        global $xoopsTpl;
        if (is_object($GLOBALS['xoTheme'])) {
            $GLOBALS['xoTheme']->addMeta('meta', 'robots', 'noindex,nofollow');
        } else {
            $xoopsTpl->assign('xoops_meta_robots', 'noindex,nofollow');
        }
    }

    /**
     * @param $userid
     *
     * @return string
     */
    public static function getLinkedUserNameFromId($userid)
    {
        $userid = (int)$userid;
        if ($userid > 0) {
            /** @var \XoopsMemberHandler $memberHandler */
            $memberHandler = xoops_getHandler('member');
            $user          = $memberHandler->getUser($userid);
            if (is_object($user)) {
                $linkeduser = '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $userid . '">' . $user->getVar('uname') . '</a>';

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
    public static function getTimestamp($time)
    {
        $moduleDirName = \basename(\dirname(__DIR__));
        xoops_loadLanguage('local', $moduleDirName);

        $trans     = [
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
            'Dec'       => _XOOPSTUBE_DEC,
        ];
        $timestamp = strtr($time, $trans);

        return $timestamp;
    }

    /**
     * Do some basic file checks and stuff.
     * Author: Andrew Mills  Email:  ajmills@sirium.net
     * from amReviews module
     */
    public static function fileChecks()
    {
        echo '<fieldset>';
        echo '<legend style="color: #990000; font-weight: bold;">' . _AM_XOOPSTUBE_FILECHECKS . '</legend>';

        $dirPhotos      = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['catimage'];
        $dirVideos      = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['videodir'];
        $dirScreenshots = XOOPS_ROOT_PATH . '/' . $GLOBALS['xoopsModuleConfig']['videoimgdir'];

        if (file_exists($dirPhotos)) {
            if (!is_writable($dirPhotos)) {
                echo '<span style=" color: red; font-weight: bold;">Warning:</span> ' . _AM_XOOPSTUBE_UNABLE_TO_WRITE . $dirPhotos . '<br>';
            } else {
                echo '<span style=" color: green; font-weight: bold;">OK:</span> ' . $dirPhotos . '<br>';
            }
        } else {
            echo '<span style=" color: red; font-weight: bold;">' . _AM_XOOPSTUBE_WARNING . '</span> ' . $dirPhotos . ' <span style=" color: red; ">' . _AM_XOOPSTUBE_NOT_EXISTS . '</span> <br>';
        }
        // photothumbdir
        if (file_exists($dirVideos)) {
            if (!is_writable($dirVideos)) {
                echo '<span style=" color: red; font-weight: bold;">' . _AM_XOOPSTUBE_WARNING . '</span> ' . _AM_XOOPSTUBE_UNABLE_TO_WRITE . $dirVideos . '<br>';
            } else {
                echo '<span style=" color: green; font-weight: bold;">OK:</span> ' . $dirVideos . '<br>';
            }
        } else {
            echo '<span style=" color: red; font-weight: bold;">' . _AM_XOOPSTUBE_WARNING . '</span> ' . $dirVideos . ' <span style=" color: red; ">' . _AM_XOOPSTUBE_NOT_EXISTS . '</span> <br>';
        }
        // photohighdir
        if (file_exists($dirScreenshots)) {
            if (!is_writable($dirScreenshots)) {
                echo '<span style=" color: red; font-weight: bold;">Warning:</span> ' . _AM_XOOPSTUBE_UNABLE_TO_WRITE . $dirScreenshots . '<br>';
            } else {
                echo '<span style=" color: green; font-weight: bold;">OK:</span> ' . $dirScreenshots . '<br>';
            }
        } else {
            echo '<span style=" color: red; font-weight: bold;">' . _AM_XOOPSTUBE_WARNING . '</span> ' . $dirScreenshots . ' <span style=" color: red; ">' . _AM_XOOPSTUBE_NOT_EXISTS . '</span> <br>';
        }

        /**
         * Some info.
         */
        $uploads = ini_get('file_uploads') ? _AM_XOOPSTUBE_UPLOAD_ON : _AM_XOOPSTUBE_UPLOAD_OFF;
        echo '<br>';
        echo '<ul>';
        echo '<li>' . _AM_XOOPSTUBE_UPLOADMAX . '<b>' . ini_get('upload_max_filesize') . '</b></li>';
        echo '<li>' . _AM_XOOPSTUBE_POSTMAX . '<b>' . ini_get('post_max_size') . '</b></li>';
        echo '<li>' . _AM_XOOPSTUBE_UPLOADS . '<b>' . $uploads . '</b></li>';

        $gdinfo = gd_info();
        if (function_exists('gd_info')) {
            echo '<li>' . _AM_XOOPSTUBE_GDIMGSPPRT . '<b>' . _AM_XOOPSTUBE_GDIMGON . '</b></li>';
            echo '<li>' . _AM_XOOPSTUBE_GDIMGVRSN . '<b>' . $gdinfo['GD Version'] . '</b></li>';
        } else {
            echo '<li>' . _AM_XOOPSTUBE_GDIMGSPPRT . '<b>' . _AM_XOOPSTUBE_GDIMGOFF . '</b></li>';
        }
        echo '</ul>';

        //$inithingy = ini_get_all();
        //print_r($inithingy);

        echo '</fieldset>';
    }

    /**
     * @param      $path
     * @param int  $mode
     * @param      $fileSource
     * @param null $fileTarget
     */
    public static function createDirectory($path, $mode, $fileSource, $fileTarget = null)
    {
        if (!is_dir($path)) {
            if (!mkdir($path, $mode) && !is_dir($path)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
            }
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
    public static function getLetters()
    {
        global $xoopsModule;

        $letterchoice          = '<div>' . _MD_XOOPSTUBE_BROWSETOTOPIC . '</div>';
        $alphabet              = getXtubeAlphabet();
        $num                   = count($alphabet) - 1;
        $counter               = 0;
        $distinctDbLetters_arr = [];
        $sql                   = 'SELECT DISTINCT (UPPER(LEFT(title, 1))) AS letter FROM ' . $GLOBALS['xoopsDB']->prefix('xoopstube_videos WHERE expired = 0 AND offline = 0');
        $result                = $GLOBALS['xoopsDB']->query($sql);
        if ($result) {
            while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
                $distinctDbLetters_arr[] = $row['letter'];
            }
        }
        unset($sql);

        //        while (list(, $ltr) = each($alphabet)) {
        foreach ($alphabet as $key => $ltr) {
            if (in_array($ltr, $distinctDbLetters_arr)) {
                $letterchoice .= '<a class="xoopstube_letters xoopstube_letters_green" href="';
            } else {
                $letterchoice .= '<a class="xoopstube_letters" href="';
            }
            $letterchoice .= XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?letter=' . $ltr . '">' . $ltr . '</a>';
            if ($counter == round($num / 2)) {
                $letterchoice .= '<br>';
            } elseif ($counter !== $num) {
                $letterchoice .= '&nbsp;';
            }
            ++$counter;
        }

        return $letterchoice;
    }

    /**
     * @return mixed|string
     */
    public static function getLettersChoice()
    {
        global $xoopsModule;

        $moduleDirName = $xoopsModule->getVar('dirname');
        require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/$moduleDirName.php";
        /** @var \Xoopstube\Helper $helper */
        $helper = Xoopstube\Helper::getInstance();

        $a             = $helper->getHandler('Xoopstube');
        $b             = $a->getActiveCriteria();
        $moduleDirName = \basename(\dirname(__DIR__));

        $criteria = $helper->getHandler('Xoopstube')->getActiveCriteria();
        $criteria->setGroupby('UPPER(LEFT(title,1))');
        $countsByLetters = $helper->getHandler($moduleDirName)->getCounts($criteria);
        // Fill alphabet array
        $alphabet       = getXtubeAlphabet();
        $alphabet_array = [];
        foreach ($alphabet as $letter) {
            $letter_array = [];
            if (isset($countsByLetters[$letter])) {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = $countsByLetters[$letter];
                $letter_array['url']    = '' . XOOPS_URL . "/modules/$moduleDirName/viewcat.php?letter={$letter}";
            } else {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = 0;
                $letter_array['url']    = '';
            }
            $alphabet_array[$letter] = $letter_array;
            unset($letter_array);
        }
        // Render output
        if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
            require_once $GLOBALS['xoops']->path('class/theme.php');
            $GLOBALS['xoTheme'] = new xos_opal_Theme();
        }
        require_once $GLOBALS['xoops']->path('class/template.php');
        $letterschoiceTpl          = new XoopsTpl();
        $letterschoiceTpl->caching = 0; // Disable cache
        $letterschoiceTpl->assign('alphabet', $alphabet_array);
        $html = $letterschoiceTpl->fetch('db:' . $helper->getModule()->dirname() . '_common_letterschoice.tpl');
        unset($letterschoiceTpl);

        return $html;
    }

    /**
     * Recursively sort categories by level and weight
     *
     * @param int $pid
     * @param int $level
     *
     * @return array array of arrays: 'pid', 'cid', 'level', 'category' as array
     *
     * @access  public
     * @author  luciorota
     */
    public static function sortCategories($pid = 0, $level = 0)
    {
        /** @var \Xoopstube\Helper $helper */
        $helper = Xoopstube\Helper::getInstance();

        $sorted   = [];
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('pid', $pid));
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        $subCategoryObjs = $helper->getHandler('Category')->getObjects($criteria);
        if (count($subCategoryObjs) > 0) {
            ++$level;
            foreach ($subCategoryObjs as $subCategoryObj) {
                $pid      = $subCategoryObj->getVar('pid');
                $cid      = $subCategoryObj->getVar('cid');
                $sorted[] = ['pid' => $pid, 'cid' => $cid, 'level' => $level, 'category' => $subCategoryObj->toArray()];
                if (false !== ($subSorted = self::sortCategories($cid, $level))) {
                    $sorted = array_merge($sorted, $subSorted);
                }
            }
        }

        return $sorted;
    }

    /**
     * Create download by letter choice bar/menu
     * updated starting from this idea https://xoops.org/modules/news/article.php?storyid=6497
     *
     * @return string html
     *
     * @access  public
     * @author  luciorota
     */
    //    public static function lettersChoice()
    //    {
    //        /** @var \Xoopstube\Helper $helper */
    //        $helper = Xoopstube\Helper::getInstance();
    //
    //        $criteria = $helper->getHandler('Videos')->getActiveCriteria();
    //        $criteria->setGroupby('UPPER(LEFT(title,1))');
    //        $countsByLetters = $helper->getHandler('Videos')->getCounts($criteria);
    //        // Fill alphabet array
    //        $alphabet       = getLocalAlphabet();
    //        $alphabetArray = [];
    //        foreach ($alphabet as $letter) {
    //            $letter_array = [];
    //            if (isset($countsByLetters[$letter])) {
    //                $letter_array['letter'] = $letter;
    //                $letter_array['count']  = $countsByLetters[$letter];
    //                $letter_array['url']    = XOOPS_URL . "/modules/{$helper->getModule()->dirname()}/viewcat.php?list={$letter}";
    //            } else {
    //                $letter_array['letter'] = $letter;
    //                $letter_array['count']  = 0;
    //                $letter_array['url']    = '';
    //            }
    //            $alphabetArray[$letter] = $letter_array;
    //            unset($letter_array);
    //        }
    //        // Render output
    //        if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
    //            require_once $GLOBALS['xoops']->path('/class/theme.php');
    //            $GLOBALS['xoTheme'] = new \xos_opal_Theme();
    //        }
    //        require_once $GLOBALS['xoops']->path('class/template.php');
    //        $letterschoiceTpl          = new \XoopsTpl();
    //        $letterschoiceTpl->caching = false; // Disable cache
    //        $letterschoiceTpl->assign('alphabet', $alphabetArray);
    //        $html = $letterschoiceTpl->fetch("db:{$helper->getModule()->dirname()}_common_letterschoice.tpl");
    //        unset($letterschoiceTpl);
    //
    //        return $html;
    //    }

    //===============  from WF-Downloads   ======================================

    /**
     * @return bool
     */
    public static function isUserAdmin()
    {
        /** @var \Xoopstube\Helper $helper */
        $helper = Xoopstube\Helper::getInstance();

        static $xtubeIsAdmin;

        if (isset($xtubeIsAdmin)) {
            return $xtubeIsAdmin;
        }

        if (!$GLOBALS['xoopsUser']) {
            $xtubeIsAdmin = false;
        } else {
            $xtubeIsAdmin = $GLOBALS['xoopsUser']->isAdmin($helper->getModule()->getVar('mid'));
        }

        return $xtubeIsAdmin;
    }

    //from Lexikon

    /**
     * @return int
     */
    public static function countCats()
    {
        global $xoopsUser, $xoopsModule;
        $grouppermHandler = xoops_getHandler('groupperm');
        $groups           = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $totalcats        = $grouppermHandler->getItemIds('lexikon_view', $groups, $xoopsModule->getVar('mid'));

        return count($totalcats);
    }

    /**
     * @return mixed
     */
    public static function countWords()
    {
        global $xoopsUser, $xoopsDB;
        $grouppermHandler = xoops_getHandler('groupperm');
        $groups           = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname('lexikon');
        $module_id     = $module->getVar('mid');
        $allowed_cats  = $grouppermHandler->getItemIds('lexikon_view', $groups, $module_id);
        $catids        = implode(',', $allowed_cats);
        $catperms      = " AND categoryID IN ($catids) ";

        $pubwords       = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('lxentries') . " WHERE submit = '0' AND offline ='0' AND request = '0' " . $catperms . ' ');
        $publishedwords = $xoopsDB->getRowsNum($pubwords);

        return $publishedwords;
    }

    /**
     * @return array
     */
    public static function getCategoryArray()
    {
        global $xoopsDB, $xoopsUser, $xoopsModule;
        /** @var \Xoopstube\Helper $helper */
        $helper           = Xoopstube\Helper::getInstance();
        $myts             = MyTextSanitizer::getInstance();
        $groups           = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $grouppermHandler = xoops_getHandler('groupperm');
        $block0           = [];
        $count            = 1;
        $resultcat        = $xoopsDB->query('SELECT categoryID, name, total, logourl FROM ' . $xoopsDB->prefix('lxcategories') . ' ORDER BY weight ASC');
        while (list($catID, $name, $total, $logourl) = $xoopsDB->fetchRow($resultcat)) {
            if ($grouppermHandler->checkRight('lexikon_view', $catID, $groups, $xoopsModule->getVar('mid'))) {
                $catlinks = [];
                ++$count;
                if ($logourl && 'http://' !== $logourl) {
                    $logourl = htmlspecialchars($logourl);
                } else {
                    $logourl = '';
                }
                $xoopsModule          = XoopsModule::getByDirname('lexikon');
                $catlinks['id']       = (int)$catID;
                $catlinks['total']    = (int)$total;
                $catlinks['linktext'] = htmlspecialchars($name);
                $catlinks['image']    = $logourl;
                $catlinks['count']    = $count;

                $block0['categories'][] = $catlinks;
            }
        }

        return $block0;
    }

    /**
     * @return array
     */
    public static function getAlphaArray()
    {
        global $xoopsUser, $xoopsDB, $xoopsModule;
        $grouppermHandler = xoops_getHandler('groupperm');
        $groups           = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname('lexikon');
        $module_id     = $module->getVar('mid');
        $allowed_cats  = $grouppermHandler->getItemIds('lexikon_view', $groups, $module_id);
        $catids        = implode(',', $allowed_cats);
        $catperms      = " AND categoryID IN ($catids) ";
        $alpha         = [];
        /**
         * @param $a
         * @return null|string|string[]
         */
        function unichr($a)
        {
            return mb_convert_encoding(pack('N', $a), mb_internal_encoding(), 'UCS-4BE');
        }

        for ($a = 48; $a < (48 + 10); ++$a) {
            $letterlinks             = [];
            $initial                 = unichr($a);
            $sql                     = $xoopsDB->query('SELECT entryID FROM ' . $xoopsDB->prefix('lxentries') . " WHERE init = '$initial' AND submit = '0' AND offline ='0' AND request = '0' " . $catperms . ' ');
            $howmany                 = $xoopsDB->getRowsNum($sql);
            $letterlinks['total']    = $howmany;
            $letterlinks['id']       = unichr($a);
            $letterlinks['linktext'] = unichr($a);

            $alpha['initial'][] = $letterlinks;
        }
        for ($a = 65; $a < (65 + 26); ++$a) {
            $letterlinks             = [];
            $initial                 = unichr($a);
            $sql                     = $xoopsDB->query('SELECT entryID FROM ' . $xoopsDB->prefix('lxentries') . " WHERE init = '$initial' AND submit = '0' AND offline ='0' AND request = '0' " . $catperms . ' ');
            $howmany                 = $xoopsDB->getRowsNum($sql);
            $letterlinks['total']    = $howmany;
            $letterlinks['id']       = unichr($a);
            $letterlinks['linktext'] = unichr($a);

            $alpha['initial'][] = $letterlinks;
        }
        /*for ($a = 1040; $a < (1040 + 32); ++$a) {
            $letterlinks             = [];
            $initial                 = unichr($a);
            $sql                     = $xoopsDB->query('SELECT entryID FROM '
                                                           . $xoopsDB->prefix('lxentries')
                                                           . " WHERE init = '$initial' AND submit = '0' AND offline ='0' AND request = '0' "
                                                           . $catperms
                                                           . '');
            $howmany                 = $xoopsDB->getRowsNum($sql);
            $letterlinks['total']    = $howmany;
            $letterlinks['id']       = unichr($a);
            $letterlinks['linktext'] = unichr($a);
            $alpha['initial'][] = $letterlinks;
        }*/

        return $alpha;
    }

    /**
     * chr() with unicode support
     * I found this on this site http://en.php.net/chr
     * don't take credit for this.
     * @param $initials
     * @return string
     */
    public static function getUchr($initials)
    {
        if (is_scalar($initials)) {
            $initials = func_get_args();
        }
        $str = '';
        foreach ($initials as $init) {
            $str .= html_entity_decode('&#' . $init . ';', ENT_NOQUOTES, 'UTF-8');
        }

        return $str;
    }
}
