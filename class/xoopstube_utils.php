<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * xoopstube
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hervé Thouzard (http://www.herve-thouzard.com/)
 * @version     $Id$
 */

/**
 * A set of useful and common functions
 *
 * @package       xoopstube
 * @author        Hervé Thouzard - Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 *
 * Note: You should be able to use it without the need to instantiate it.
 *
 */
// defined('XOOPS_ROOT_PATH') || die('XOOPS Root Path not defined');

/**
 * Class XoopstubeUtilities
 */
class XoopstubeUtilities
{

    const MODULE_NAME = 'xoopstube';

    /**
     * Access the only instance of this class
     *
     * @return object
     *
     * @static
     * @staticvar   object
     */
    public function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new oledrion_utils();
        }

        return $instance;
    }

    /**
     * Returns a module's option (with cache)
     *
     * @param string  $option    module option's name
     * @param boolean $withCache Do we have to use some cache ?
     *
     * @return mixed option's value
     */
    public static function getModuleOption($option, $withCache = true)
    {
        global $xoopsModuleConfig, $xoopsModule;
        $repmodule = self::MODULE_NAME;
        static $options = array();
        if (is_array($options) && array_key_exists($option, $options) && $withCache) {
            return $options[$option];
        }

        $retval = false;
        if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule && $xoopsModule->getVar('isactive'))) {
            if (isset($xoopsModuleConfig[$option])) {
                $retval = $xoopsModuleConfig[$option];
            }
        } else {
            $module_handler = xoops_gethandler('module');
            $module         = $module_handler->getByDirname($repmodule);
            $config_handler = xoops_gethandler('config');
            if ($module) {
                $moduleConfig = $config_handler->getConfigsByCat(0, $module->getVar('mid'));
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
     * @return boolean
     */
    public function isX23()
    {
        $x23 = false;
        $xv  = str_replace('XOOPS ', '', XOOPS_VERSION);
        if (intval(substr($xv, 2, 1)) >= 3) {
            $x23 = true;
        }

        return $x23;
    }

    /**
     * Is Xoops 2.0.x ?
     *
     * @return boolean
     */
    public static function isX20()
    {
        $x20 = false;
        $xv  = str_replace('XOOPS ', '', XOOPS_VERSION);
        if (substr($xv, 2, 1) == '0') {
            $x20 = true;
        }

        return $x20;
    }

    /**
     * Create (in a link) a javascript confirmation's box
     *
     * @param string  $message Message to display
     * @param boolean $form    Is this a confirmation for a form ?
     *
     * @return string the javascript code to insert in the link (or in the form)
     */
    public static function javascriptLinkConfirm($message, $form = false)
    {
        if (!$form) {
            return "onclick=\"javascript:return confirm('" . str_replace("'", " ", $message) . "')\"";
        } else {
            return "onSubmit=\"javascript:return confirm('" . str_replace("'", " ", $message) . "')\"";
        }
    }

    /**
     * Get current user IP
     *
     * @return string IP address (format Ipv4)
     */
    public static function IP()
    {
        $proxy_ip = '';
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
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
        $regs = array();
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
     *
     * @return void
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
     * @internal param string $tpl_name Template's name
     * @return boolean Result of the send
     */
    public static function sendEmailFromTpl($tplName, $recipients, $subject, $variables)
    {
        global $xoopsConfig;
        require_once XOOPS_ROOT_PATH . '/class/xoopsmailer.php';
        if (!is_array($recipients)) {
            if (trim($recipients) == '') {
                return false;
            }
        } else {
            if (count($recipients) == 0) {
                return false;
            }
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
        if (!file_exists($filename)) {
            $fp = @fopen($filename, 'a');
            if ($fp) {
                fwrite($fp, "<?php exit(); ?>\n");
                fclose($fp);
            }
        }
        $fp = @fopen($filename, 'a');

        if ($fp) {
            fwrite($fp, str_repeat('-', 120) . "\n");
            fwrite($fp, date('d/m/Y H:i:s') . "\n");
            fwrite($fp, "Template name : " . $tplName . "\n");
            fwrite($fp, "Email subject : " . $subject . "\n");
            if (is_array($recipients)) {
                fwrite($fp, "Recipient(s) : " . implode(',', $recipients) . "\n");
            } else {
                fwrite($fp, "Recipient(s) : " . $recipients . "\n");
            }
            fwrite($fp, "Transmited variables : " . implode(',', $variables) . "\n");
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
        $tpllist = array();
        require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
        require_once XOOPS_ROOT_PATH . '/class/template.php';
        $tplfile_handler = xoops_gethandler('tplfile');
        $tpllist         = $tplfile_handler->find(null, null, null, $folder);
        xoops_template_clear_module_cache($xoopsModule->getVar('mid')); // Clear module's blocks cache

        foreach ($tpllist as $onetemplate) { // Remove cache for each page.
            if ($onetemplate->getVar('tpl_type') == 'module') {
                //	Note, I've been testing all the other methods (like the one of Smarty) and none of them run, that's why I have used this code
                $files_del = array();
                $files_del = glob(XOOPS_CACHE_PATH . '/*' . $onetemplate->getVar('tpl_file') . '*');
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
     * @param        integer  timeout Time to wait before to redirect
     */
    public static function redirect($message = '', $url = 'index.php', $time = 2)
    {
        redirect_header($url, $time, $message);
        exit();
    }

    /**
     * Internal function used to get the handler of the current module
     *
     * @return object The module
     */
    protected function _getModule()
    {
        static $mymodule;
        if (!isset($mymodule)) {
            global $xoopsModule;
            if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == OLEDRION_DIRNAME) {
                $mymodule = $xoopsModule;
            } else {
                $hModule  = xoops_gethandler('module');
                $mymodule = $hModule->getByDirname(OLEDRION_DIRNAME);
            }
        }

        return $mymodule;
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
    public function getUsersFromGroup($groupId)
    {
        $users          = array();
        $member_handler = xoops_gethandler('member');
        $users          = $member_handler->getUsersByGroup($groupId, true);

        return $users;
    }

    /**
     * Retourne la liste des emails des utilisateurs membres d'un groupe
     *
     * @param $groupId
     *
     * @internal param int $group_id Group's number
     * @return array Emails list
     */
    public static function getEmailsFromGroup($groupId)
    {
        $ret   = array();
        $users = self::getUsersFromGroup($groupId);
        foreach ($users as $user) {
            $ret[] = $user->getVar('email');
        }

        return $ret;
    }

    /**
     * Vérifie que l'utilisateur courant fait partie du groupe des administrateurs
     *
     * @return booleean Admin or not
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
    public function getCurrentSQLDateTime()
    {
        return date('Y-m-d H:i:s'); // 2007-05-02
    }

    /**
     * Convert a Mysql date to the human's format
     *
     * @param string $date   The date to convert
     * @param string $format
     *
     * @return string The date in a human form
     */
    public function SQLDateToHuman($date, $format = 'l')
    {
        if ($date != '0000-00-00' && xoops_trim($date) != '') {
            return formatTimestamp(strtotime($date), $format);
        } else {
            return '';
        }
    }

    /**
     * Convert a timestamp to a Mysql date
     *
     * @param integer $timestamp The timestamp to use
     *
     * @return string The date in the Mysql format
     */
    public function timestampToMysqlDate($timestamp)
    {
        return date('Y-m-d', intval($timestamp));
    }

    /**
     * Conversion d'un dateTime Mysql en date lisible en français
     *
     * @param $dateTime
     *
     * @return bool|string
     */
    public function sqlDateTimeToFrench($dateTime)
    {
        return date('d/m/Y H:i:s', strtotime($dateTime));
    }

    /**
     * Convert a timestamp to a Mysql datetime form
     *
     * @param integer $timestamp The timestamp to use
     *
     * @return string The date and time in the Mysql format
     */
    public function timestampToMysqlDateTime($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * This function indicates if the current Xoops version needs to add asterisks to required fields in forms
     *
     * @return boolean Yes = we need to add them, false = no
     */
    public function needsAsterisk()
    {
        if (self::isX23()) {
            return false;
        }
        if (strpos(strtolower(XOOPS_VERSION), 'impresscms') !== false) {
            return false;
        }
        if (strpos(strtolower(XOOPS_VERSION), 'legacy') === false) {
            $xv = xoops_trim(str_replace('XOOPS ', '', XOOPS_VERSION));
            if (intval(substr($xv, 4, 2)) >= 17) {
                return false;
            }
        }

        return true;
    }

    /**
     * Mark the mandatory fields of a form with a star
     *
     * @param object $sform The form to modify
     *
     * @internal param string $caracter The character to use to mark fields
     * @return object The modified form
     */
    public static function &formMarkRequiredFields(&$sform)
    {
        if (self::needsAsterisk()) {
            $required = array();
            foreach ($sform->getRequired() as $item) {
                $required[] = $item->_name;
            }
            $elements = array();
            $elements = $sform->getElements();
            $cnt      = count($elements);
            for ($i = 0; $i < $cnt; ++$i) {
                if (is_object($elements[$i]) && in_array($elements[$i]->_name, $required)
                ) {
                    $elements[$i]->_caption .= ' *';
                }
            }
        }

        return $sform;
    }

    /**
     * Create an html heading (from h1 to h6)
     *
     * @param string  $title The text to use
     * @param integer $level Level to return
     *
     * @return string The heading
     */
    public static function htitle($title = '', $level = 1)
    {
        printf("<h%01d>%s</h%01d>", $level, $title, $level);
    }

    /**
     * Create a unique upload filename
     *
     * @param string  $folder   The folder where the file will be saved
     * @param string  $fileName Original filename (coming from the user)
     * @param boolean $trimName Do we need to create a "short" unique name ?
     *
     * @return string The unique filename to use (with its extension)
     */
    public function createUploadName($folder, $fileName, $trimName = false)
    {
        $workingfolder = $folder;
        if (xoops_substr($workingfolder, strlen($workingfolder) - 1, 1) != '/') {
            $workingfolder .= '/';
        }
        $ext  = basename($fileName);
        $ext  = explode('.', $ext);
        $ext  = '.' . $ext[count($ext) - 1];
        $true = true;
        while ($true) {
            $ipbits = explode('.', $_SERVER['REMOTE_ADDR']);
            list($usec, $sec) = explode(' ', microtime());
            $usec = (integer)($usec * 65536);
            $sec  = ((integer)$sec) & 0xFFFF;

            if ($trimName) {
                $uid = sprintf("%06x%04x%04x", ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            } else {
                $uid = sprintf("%08x-%04x-%04x", ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
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
    public function unhtml($chaine)
    {
        $search = $replace = array();
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
        $search[]  = "";
        $replace[] = '-';
        $search[]  = "&bull;"; // $replace[] = '';
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
     * Création d'une titre pour être utilisé par l'url rewriting
     *
     * @param string  $content Le texte à utiliser pour créer l'url
     * @param integer $urw     La limite basse pour créer les mots
     *
     * @return string Le texte à utiliser pour l'url
     *                Note, some parts are from Solo's code
     */
    public static function makeSeoUrl($content, $urw = 1)
    {
        $s       = "ÀÁÂÃÄÅÒÓÔÕÖØÈÉÊËÇÌÍÎÏÙÚÛÜÑàáâãäåòóôõöøèéêëçìíîïùúûüÿñ '()";
        $r       = "AAAAAAOOOOOOEEEECIIIIUUUUYNaaaaaaooooooeeeeciiiiuuuuyn----";
        $content = self::unhtml($content); // First, remove html entities
        $content = strtr($content, $s, $r);
        $content = strip_tags($content);
        $content = strtolower($content);
        $content = htmlentities($content); // TODO: Vérifier
        $content = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/', '$1', $content);
        $content = html_entity_decode($content);
        $content = preg_replace('/quot/i', ' ', $content);
        $content = preg_replace("/'/i", ' ', $content);
        $content = preg_replace('/-/i', ' ', $content);
        $content = preg_replace('/[[:punct:]]/i', '', $content);

        // Selon option mais attention au fichier .htaccess !
        // $content = eregi_replace('[[:digit:]]','', $content);
        $content = preg_replace("/[^a-z|A-Z|0-9]/", '-', $content);

        $words    = explode(' ', $content);
        $keywords = '';
        foreach ($words as $word) {
            if (strlen($word) >= $urw) {
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
        if (substr($keywords, strlen($keywords) - 1, 1) == '-') {
            $keywords = substr($keywords, 0, strlen($keywords) - 1);
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

        $tmp = array();
        // Search for the "Minimum keyword length"
        if (isset($_SESSION['oledrion_keywords_limit'])) {
            $limit = $_SESSION['oledrion_keywords_limit'];
        } else {
            $config_handler                      = xoops_gethandler('config');
            $xoopsConfigSearch                   = $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);
            $limit                               = $xoopsConfigSearch['keyword_min'];
            $_SESSION['oledrion_keywords_limit'] = $limit;
        }
        $myts            = MyTextSanitizer::getInstance();
        $content         = str_replace("<br />", " ", $content);
        $content         = $myts->undoHtmlSpecialChars($content);
        $content         = strip_tags($content);
        $content         = strtolower($content);
        $search_pattern  = array("&nbsp;", "\t", "\r\n", "\r", "\n", ",", ".", "'", ";", ":", ")", "(", '"', '?', '!', '{', '}', '[', ']', '<', '>', '/', '+', '-', '_', '\\', '*');
        $replace_pattern = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
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
        if (xoops_trim(self::getModuleOption('metagen_blacklist')) != '') {
            $metagen_blacklist = str_replace("\r", '', self::getModuleOption('metagen_blacklist'));
            $metablack         = explode("\n", $metagen_blacklist);
            array_walk($metablack, 'trim');
            $keywords = array_diff($keywords, $metablack);
        }

        foreach ($keywords as $keyword) {
            if (strlen($keyword) >= $limit && !is_numeric($keyword)) {
                $tmp[] = $keyword;
            }
        }
        $tmp = array_slice($tmp, 0, $keywordscount);
        if (count($tmp) > 0) {
            return implode(',', $tmp);
        } else {
            if (!isset($config_handler) || !is_object($config_handler)) {
                $config_handler = xoops_gethandler('config');
            }
            $xoopsConfigMetaFooter = $config_handler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
            if (isset($xoopsConfigMetaFooter['meta_keywords'])) {
                return $xoopsConfigMetaFooter['meta_keywords'];
            } else {
                return '';
            }
        }
    }

    /**
     * Fonction chargée de gérer l'upload
     *
     * @param integer $indice        L'indice du fichier à télécharger
     * @param string  $dstpath
     * @param null    $mimeTypes
     * @param null    $uploadMaxSize
     * @param null    $maxWidth
     * @param null    $maxHeight
     *
     * @return mixed True si l'upload s'est bien déroulé sinon le message d'erreur correspondant
     */
    public static function uploadFile($indice, $dstpath = XOOPS_UPLOAD_PATH, $mimeTypes = null, $uploadMaxSize = null, $maxWidth = null, $maxHeight = null)
    {
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        global $destname;
        if (isset($_POST['xoops_upload_file'])) {
            require_once XOOPS_ROOT_PATH . '/class/uploader.php';
            $fldname = '';
            $fldname = $_FILES[$_POST['xoops_upload_file'][$indice]];
            $fldname = (get_magic_quotes_gpc()) ? stripslashes($fldname['name']) : $fldname['name'];
            if (xoops_trim($fldname != '')) {
                $destname = self::createUploadName($dstpath, $fldname, true);
                if ($mimeTypes === null) {
                    $permittedtypes = explode("\n", str_replace("\r", '', self::getModuleOption('mimetypes')));
                    array_walk($permittedtypes, 'trim');
                } else {
                    $permittedtypes = $mimeTypes;
                }
                if ($uploadMaxSize === null) {
                    $uploadSize = self::getModuleOption('maxuploadsize');
                } else {
                    $uploadSize = $uploadMaxSize;
                }
                $uploader = new XoopsMediaUploader($dstpath, $permittedtypes, $uploadSize, $maxWidth, $maxHeight);
                //$uploader->allowUnknownTypes = true;
                $uploader->setTargetFileName($destname);
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][$indice])) {
                    if ($uploader->upload()) {
                        return true;
                    } else {
                        return _ERRORS . ' ' . htmlentities($uploader->getErrors());
                    }
                } else {
                    return htmlentities($uploader->getErrors());
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Resize a Picture to some given dimensions (using the wideImage library)
     *
     * @param string  $src_path      Picture's source
     * @param string  $dst_path      Picture's destination
     * @param integer $param_width   Maximum picture's width
     * @param integer $param_height  Maximum picture's height
     * @param boolean $keep_original Do we have to keep the original picture ?
     * @param string  $fit           Resize mode (see the wideImage library for more information)
     *
     * @return bool
     */
    public function resizePicture($src_path, $dst_path, $param_width, $param_height, $keep_original = false, $fit = 'inside')
    {
        require_once OLEDRION_PATH . 'class/wideimage/WideImage.inc.php';
        $resize = true;
        if (OLEDRION_DONT_RESIZE_IF_SMALLER) {
            $pictureDimensions = getimagesize($src_path);
            if (is_array($pictureDimensions)) {
                $width  = $pictureDimensions[0];
                $height = $pictureDimensions[1];
                if ($width < $param_width && $height < $param_height) {
                    $resize = false;
                }
            }

        }

        $img = wiImage::load($src_path);
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
     * Déclenchement d'une alerte Xoops suite à un évènement
     *
     * @param string       $category La catégorie de l'évènement
     * @param integer      $itemId   L'ID de l'élément (trop général pour être décris précisément)
     * @param unknown_type $event    L'évènement qui est déclencé
     * @param unknown_type $tags     Les variables à passer au template
     */
    public function notify($category, $itemId, $event, $tags)
    {
        $notification_handler = xoops_gethandler('notification');
        $tags['X_MODULE_URL'] = OLEDRION_URL;
        $notification_handler->triggerEvent($category, $itemId, $event, $tags);
    }

    /**
     * Ajoute des jours à une date et retourne la nouvelle date au format Date de Mysql
     *
     * @param int     $duration
     * @param integer $startingDate Date de départ (timestamp)
     *
     * @internal param int $durations Durée en jours
     * @return bool|string
     */
    public function addDaysToDate($duration = 1, $startingDate = 0)
    {
        if ($startingDate == 0) {
            $startingDate = time();
        }
        $endingDate = $startingDate + ($duration * 86400);

        return date('Y-m-d', $endingDate);
    }

    /**
     * Retourne un breadcrumb en fonction des paramètres passés et en partant (d'office) de la racine du module
     *
     * @param array  $path  Le chemin complet (excepté la racine) du breadcrumb sous la forme clé=url valeur=titre
     * @param string $raquo Le séparateur par défaut à utiliser
     *
     * @return string le breadcrumb
     */
    public static function breadcrumb($path, $raquo = ' &raquo; ')
    {
        $breadcrumb        = '';
        $workingBreadcrumb = array();
        if (is_array($path)) {
            $moduleName          = self::getModuleName();
            $workingBreadcrumb[] = "<a href='" . OLEDRION_URL . "' title='" . self::makeHrefTitle($moduleName) . "'>" . $moduleName . '</a>';
            foreach ($path as $url => $title) {
                $workingBreadcrumb[] = "<a href='" . $url . "'>" . $title . '</a>';
            }
            $cnt = count($workingBreadcrumb);
            for ($i = 0; $i < $cnt; ++$i) {
                if ($i == $cnt - 1) {
                    $workingBreadcrumb[$i] = strip_tags($workingBreadcrumb[$i]);
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
    public function close_tags($string)
    {
        // match opened tags
        if (preg_match_all('/<([a-z\:\-]+)[^\/]>/', $string, $start_tags)) {
            $start_tags = $start_tags[1];

            // match closed tags
            if (preg_match_all('/<\/([a-z]+)>/', $string, $end_tags)) {
                $complete_tags = array();
                $end_tags      = $end_tags[1];

                foreach ($start_tags as $key => $val) {
                    $posb = array_search($val, $end_tags);
                    if (is_integer($posb)) {
                        unset($end_tags[$posb]);
                    } else {
                        $complete_tags[] = $val;
                    }
                }
            } else {
                $complete_tags = $start_tags;
            }

            $complete_tags = array_reverse($complete_tags);
            for ($i = 0; $i < count($complete_tags); ++$i) {
                $string .= '</' . $complete_tags[$i] . '>';
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
    public function truncate_tagsafe($string, $length = 80, $etc = '...', $break_words = false)
    {
        if ($length == 0) {
            return '';
        }

        if (strlen($string) > $length) {
            $length -= strlen($etc);
            if (!$break_words) {
                $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
                $string = preg_replace('/<[^>]*$/', '', $string);
                $string = self::close_tags($string);
            }

            return $string . $etc;
        } else {
            return $string;
        }
    }

    /**
     * Create an infotip
     * @param $text
     * @return string
*/
    public function makeInfotips($text)
    {
        $ret      = '';
        $infotips = self::getModuleOption('infotips');
        if ($infotips > 0) {
            $myts = MyTextSanitizer::getInstance();
            $ret  = $myts->htmlSpecialChars(xoops_substr(strip_tags($text), 0, $infotips));
        }

        return $ret;
    }

    /**
     * Mise en place de l'appel à la feuille de style du module dans le template
     * @param string $url
*/
    public static function setCSS($url = '')
    {
        global $xoopsTpl, $xoTheme;
        if ($url == '') {
            $url = OLEDRION_URL . 'css/oledrion.css';
        }

        if (!is_object($xoTheme)) {
            $xoopsTpl->assign('xoops_module_header', $xoopsTpl->get_template_vars('xoops_module_header') . "<link rel=\"stylesheet\" type=\"text/css\" href=\"$url\" />");
        } else {
            $xoTheme->addStylesheet($url);
        }
    }

    /**
     * Mise en place de l'appel à la feuille de style du module dans le template
     * @param string $language
*/
    public static function setLocalCSS($language = 'english')
    {
        global $xoopsTpl, $xoTheme;

        $localcss = OLEDRION_URL . 'language/' . $language . '/style.css';

        if (!is_object($xoTheme)) {
            $xoopsTpl->assign('xoops_module_header', $xoopsTpl->get_template_vars('xoops_module_header') . "<link rel=\"stylesheet\" type=\"text/css\" href=\"$localcss\" />");
        } else {
            $xoTheme->addStylesheet($localcss);
        }
    }

    /**
     * Calcul du TTC à partir du HT et de la TVA
     *
     * @param float   $ht     Montant HT
     * @param float   $vat    Taux de TVA
     * @param boolean $edit   Si faux alors le montant est formaté pour affichage sinon il reste tel quel
     * @param string  $format Format d'affichage du résultat (long ou court)
     *
     * @return mixed Soit une chaine soit un flottant
     */
    public static function getTTC($ht, $vat, $edit = false, $format = 's')
    {
        $oledrion_Currency = oledrion_Currency::getInstance();
        $ttc               = $ht * (1 + ($vat / 100));
        if (!$edit) {
            return $oledrion_Currency->amountForDisplay($ttc, $format);
        } else {
            return $ttc;
        }
    }

    /**
     * Renvoie le montant de la tva à partir du montant HT
     * @param $ht
     * @param $vat
     * @return float
*/
    public function getVAT($ht, $vat)
    {
        return floatval(($ht * $vat) / 100);
    }

    /**
     * Retourne le montant TTC
     *
     * @param floatval $product_price Le montant du produit
     * @param integer  $vat_id        Le numéro de TVA
     *
     * @return floatval Le montant TTC si on a trouvé sa TVA sinon
     */
    public static function getAmountWithVat($product_price, $vat_id)
    {
        static $vats = array();
        $vat_rate = null;
        if (is_array($vats) && in_array($vat_id, $vats)) {
            $vat_rate = $vats[$vat_id];
        } else {
            $handlers = oledrion_handler::getInstance();
            $vat      = null;
            $vat      = $handlers->h_oledrion_vat->get($vat_id);
            if (is_object($vat)) {
                $vat_rate      = $vat->getVar('vat_rate', 'e');
                $vats[$vat_id] = $vat_rate;
            }
        }

        if (!is_null($vat_rate)) {
            return (floatval($product_price) * floatval($vat_rate) / 100) + floatval($product_price);
        } else {
            return $product_price;
        }
    }

    /**
     * @param $datastream
     * @param $url
     *
     * @return string
     */
    public function postIt($datastream, $url)
    {
        $url     = preg_replace("@^http://@i", "", $url);
        $host    = substr($url, 0, strpos($url, "/"));
        $uri     = strstr($url, "/");
        $reqbody = '';
        foreach ($datastream as $key => $val) {
            if (!empty($reqbody)) {
                $reqbody .= "&";
            }
            $reqbody .= $key . "=" . urlencode($val);
        }
        $contentlength = strlen($reqbody);
        $reqheader     = "POST $uri HTTP/1.1\r\n" . "Host: $host\n" . "Content-Type: application/x-www-form-urlencoded\r\n" . "Content-Length: $contentlength\r\n\r\n" . "$reqbody\r\n";

        return $reqheader;
    }

    /**
     * Retourne le type Mime d'un fichier en utilisant d'abord finfo puis mime_content
     *
     * @param string $filename Le fichier (avec son chemin d'accès complet) dont on veut connaître le type mime
     *
     * @return string
     */
    public static function getMimeType($filename)
    {
        if (function_exists('finfo_open')) {
            $finfo    = finfo_open();
            $mimetype = finfo_file($finfo, $filename, FILEINFO_MIME_TYPE);
            finfo_close($finfo);

            return $mimetype;
        } else {
            if (function_exists('mime_content_type')) {
                return mime_content_type($filename);
            } else {
                return '';
            }
        }
    }

    /**
     * Retourne un criteria compo qui permet de filtrer les produits sur le mois courant
     *
     * @return object
     */
    public function getThisMonthCriteria()
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
        $users = array();
        if (is_array($xoopsUsersIDs) && count($xoopsUsersIDs) > 0) {
            $xoopsUsersIDs = array_unique($xoopsUsersIDs);
            sort($xoopsUsersIDs);
            if (count($xoopsUsersIDs) > 0) {
                $member_handler = xoops_gethandler('user');
                $criteria       = new Criteria('uid', '(' . implode(',', $xoopsUsersIDs) . ')', 'IN');
                $criteria->setSort('uid');
                $users = $member_handler->getObjects($criteria, true);
            }
        }

        return $users;
    }

    /**
     * Retourne l'ID de l'utilisateur courant (s'il est connecté)
     *
     * @return integer L'uid ou 0
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
    public function getMemberGroups($uid = 0)
    {
        static $buffer = array();
        if ($uid == 0) {
            $uid = self::getCurrentUserID();
        }

        if (is_array($buffer) && count($buffer) > 0 && isset($buffer[$uid])) {
            return $buffer[$uid];
        } else {
            if ($uid > 0) {
                $member_handler = xoops_gethandler('member');
                $buffer[$uid]   = $member_handler->getGroupsByUser($uid, false); // Renvoie un tableau d'ID (de groupes)
            } else {
                $buffer[$uid] = array(XOOPS_GROUP_ANONYMOUS);
            }
        }

        return $buffer[$uid];
    }

    /**
     * Indique si l'utilisateur courant fait partie d'une groupe donné (avec gestion de cache)
     *
     * @param integer $group Groupe recherché
     * @param int     $uid
     *
     * @return boolean vrai si l'utilisateur fait partie du groupe, faux sinon
     */
    public static function isMemberOfGroup($group = 0, $uid = 0)
    {
        static $buffer = array();
        $retval = false;
        if ($uid == 0) {
            $uid = self::getCurrentUserID();
        }
        if (is_array($buffer) && array_key_exists($group, $buffer)) {
            $retval = $buffer[$group];
        } else {
            $member_handler = xoops_gethandler('member');
            $groups         = $member_handler->getGroupsByUser($uid, false); // Renvoie un tableau d'ID (de groupes)
            $retval         = in_array($group, $groups);
            $buffer[$group] = $retval;
        }

        return $retval;
    }

    /**
     * Fonction chargée de vérifier qu'un répertoire existe, qu'on peut écrire dedans et création d'un fichier index.html
     *
     * @param string $folder Le chemin complet du répertoire à vérifier
     *
     * @return void
     */
    public static function prepareFolder($folder)
    {
        if (!is_dir($folder)) {
            mkdir($folder, 0777);
            file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
        }
        chmod($folder, 0777);
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
    public function duplicateFile($path, $filename)
    {
        $newName = self::createUploadName($path, $filename);
        if (copy($path . DIRECTORY_SEPARATOR . $filename, $path . DIRECTORY_SEPARATOR . $newName)) {
            return $newName;
        } else {
            return false;
        }
    }

    /**
     * Load a language file
     *
     * @param string $languageFile     The required language file
     * @param string $defaultExtension Default extension to use
     *
     * @since 2.2.2009.02.13
     */
    public static function loadLanguageFile($languageFile, $defaultExtension = '.php')
    {
        global $xoopsConfig;
        $root = OLEDRION_PATH;
        if (strstr($languageFile, $defaultExtension) === false) {
            $languageFile .= $defaultExtension;
        }
        if (file_exists($root . 'language' . DIRECTORY_SEPARATOR . $xoopsConfig['language'] . DIRECTORY_SEPARATOR . $languageFile)) {
            require_once $root . 'language' . DIRECTORY_SEPARATOR . $xoopsConfig['language'] . DIRECTORY_SEPARATOR . $languageFile;
        } else { // Fallback
            require_once $root . 'language' . DIRECTORY_SEPARATOR . 'english' . DIRECTORY_SEPARATOR . $languageFile;
        }
    }

    /**
     * Formatage d'un floattant pour la base de données
     *
     * @param float    Le montant à formater
     *
     * @return string le montant formaté
     * @since 2.2.2009.02.25
     */
    public static function formatFloatForDB($amount)
    {
        return number_format($amount, 2, '.', '');
    }

    /**
     * Appelle un fichier Javascript à la manière de Xoops
     *
     * @note, l'url complète ne doit pas être fournie, la méthode se charge d'ajouter
     * le chemin vers le répertoire js en fonction de la requête, c'est à dire que si
     * on appelle un fichier de langue, la méthode ajoute l'url vers le répertoire de
     * langue, dans le cas contraire on ajoute l'url vers le répertoire JS du module.
     *
     * @param string $javascriptFile
     * @param bool   $inLanguageFolder
     * @param bool   $oldWay
     *
     * @return void
     * @since 2.3.2009.03.14
     */
    public static function callJavascriptFile($javascriptFile, $inLanguageFolder = false, $oldWay = false)
    {
        global $xoopsConfig, $xoTheme;
        $fileToCall = $javascriptFile;
        if ($inLanguageFolder) {
            $root    = OLEDRION_PATH;
            $rootUrl = OLEDRION_URL;
            if (file_exists($root . 'language' . DIRECTORY_SEPARATOR . $xoopsConfig['language'] . DIRECTORY_SEPARATOR . $javascriptFile)) {
                $fileToCall = $rootUrl . 'language/' . $xoopsConfig['language'] . '/' . $javascriptFile;
            } else { // Fallback
                $fileToCall = $rootUrl . 'language/english/' . $javascriptFile;
            }
        } else {
            $fileToCall = OLEDRION_JS_URL . $javascriptFile;
        }

        $xoTheme->addScript("browse.php?Frameworks/jquery/jquery.js");
        $xoTheme->addScript($fileToCall);
    }

    /**
     * Create the <option> of an html select
     *
     * @param array $array    Array of index and labels
     * @param mixed $default  the default value
     * @param bool  $withNull
     *
     * @return string
     * @since 2.3.2009.03.13
     */
    public function htmlSelectOptions($array, $default = 0, $withNull = true)
    {
        $ret      = array();
        $selected = '';
        if ($withNull) {
            if ($default === 0) {
                $selected = " selected = 'selected'";
            }
            $ret[] = "<option value=0" . $selected . ">---</option>";
        }

        foreach ($array as $index => $label) {
            $selected = '';
            if ($index == $default) {
                $selected = " selected = 'selected'";
            }
            $ret[] = "<option value=\"" . $index . "\"" . $selected . ">" . $label . '</option>';
        }

        return implode("\n", $ret);
    }

    /**
     * Creates an html select
     *
     * @param string  $selectName Selector's name
     * @param array   $array      Options
     * @param mixed   $default    Default's value
     * @param boolean $withNull   Do we include a null option ?
     *
     * @return string
     * @since 2.3.2009.03.13
     */
    public static function htmlSelect($selectName, $array, $default, $withNull = true)
    {
        $ret = '';
        $ret .= "<select name='" . $selectName . "' id='" . $selectName . "'>\n";
        $ret .= self::htmlSelectOptions($array, $default, $withNull);
        $ret .= "</select>\n";

        return $ret;
    }

    /**
     * Extrait l'id d'une chaine formatée sous la forme xxxx-99 (duquel on récupère 99)
     *
     * @note: utilisé par les attributs produits
     *
     * @param string $string    La chaine de travail
     * @param string $separator Le séparateur
     *
     * @return string
     */
    public function getId($string, $separator = '_')
    {
        $pos = strrpos($string, $separator);
        if ($pos === false) {
            return $string;
        } else {
            return intval(substr($string, $pos + 1));
        }
    }

    /**
     * Fonction "inverse" de getId (depuis xxxx-99 on récupère xxxx)
     *
     * @note: utilisé par les attributs produits
     *
     * @param string $string    La chaine de travail
     * @param string $separator Le séparateur
     *
     * @return string
     */
    public function getName($string, $separator = '_')
    {
        $pos = strrpos($string, $separator);
        if ($pos === false) {
            return $string;
        } else {
            return substr($string, 0, $pos);
        }
    }

    /**
     * Renvoie un montant nul si le montant est négatif
     *
     * @param float $amount
     *
     * @return float
     */
    public static function doNotAcceptNegativeAmounts(&$amount)
    {
        if ($amount < 0) {
            $amount = 0;
        }
    }

    /**
     * Returns a string from the request
     *
     * @param string $valueName    Name of the parameter you want to get
     * @param mixed  $defaultValue Default value to return if the parameter is not set in the request
     *
     * @return mixed
     */
    public function getFromRequest($valueName, $defaultValue = '')
    {
        return isset($_REQUEST[$valueName]) ? $_REQUEST[$valueName] : $defaultValue;
    }

    /**
     * Verify that a mysql table exists
     *
     * @package       Oledrion
     * @author        Instant Zero (http://xoops.instant-zero.com)
     * @copyright (c) Instant Zero
     * @param $tablename
     * @return bool
*/
    public static function tableExists($tablename)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");

        return ($xoopsDB->getRowsNum($result) > 0);
    }

    /**
     * Verify that a field exists inside a mysql table
     *
     * @package       Oledrion
     * @author        Instant Zero (http://xoops.instant-zero.com)
     * @copyright (c) Instant Zero
     * @param $fieldname
     * @param $table
     * @return bool
*/
    public static function fieldExists($fieldname, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW COLUMNS FROM $table LIKE '$fieldname'");

        return ($xoopsDB->getRowsNum($result) > 0);
    }

    /**
     * Retourne la définition d'un champ
     *
     * @param string $fieldname
     * @param string $table
     *
     * @return array
     */
    public function getFieldDefinition($fieldname, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW COLUMNS FROM $table LIKE '$fieldname'");
        if ($result) {
            return $xoopsDB->fetchArray($result);
        }

        return '';
    }

    /**
     * Add a field to a mysql table
     *
     * @package       Oledrion
     * @author        Instant Zero (http://xoops.instant-zero.com)
     * @copyright (c) Instant Zero
     * @param $field
     * @param $table
     * @return mixed
*/
    public static function addField($field, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("ALTER TABLE $table ADD $field;");

        return $result;
    }

    /**
     * @param $info
     *
     * @return string
     */
    public function packingHtmlSelect($info)
    {
        $ret = '';
        $ret .= '<div class="oledrion_htmlform">';
        $ret .= '<img class="oledrion_htmlimage" src="' . $info['packing_image_url'] . '" alt="' . $info['packing_title'] . '" />';
        $ret .= '<h3>' . $info['packing_title'] . '</h3>';
        if ($info['packing_price'] > 0) {
            $ret .= '<p><span class="bold">' . _OLEDRION_PRICE . '</span> : ' . $info['packing_price_fordisplay'] . '</p>';
        } else {
            $ret .= '<p><span class="bold">' . _OLEDRION_PRICE . '</span> : ' . _OLEDRION_FREE . '</p>';
        }
        $ret .= '<p>' . $info['packing_description'] . '</p>';
        $ret .= '</div>';

        return $ret;
    }

    /**
     * @param $info
     *
     * @return string
     */
    public function deliveryHtmlSelect($info)
    {
        $ret = '';
        $ret .= '<div class="oledrion_htmlform">';
        $ret .= '<img class="oledrion_htmlimage" src="' . $info['delivery_image_url'] . '" alt="' . $info['delivery_title'] . '" />';
        $ret .= '<h3>' . $info['delivery_title'] . '</h3>';
        if ($info['delivery_price'] > 0) {
            $ret .= '<p><span class="bold">' . _OLEDRION_PRICE . '</span> : ' . $info['delivery_price_fordisplay'] . '</p>';
        } else {
            $ret .= '<p><span class="bold">' . _OLEDRION_PRICE . '</span> : ' . _OLEDRION_FREE . '</p>';
        }
        $ret .= '<p><span class="bold">' . _OLEDRION_DELIVERY_TIME . '</span> : ' . $info['delivery_time'] . _OLEDRION_DELIVERY_DAY . '</p>';
        $ret .= '<p>' . $info['delivery_description'] . '</p>';
        $ret .= '</div>';

        return $ret;
    }

    /**
     * @param $info
     *
     * @return string
     */
    public function paymentHtmlSelect($info)
    {
        $ret = '';
        $ret .= '<div class="oledrion_htmlform">';
        $ret .= '<img class="oledrion_htmlimage" src="' . $info['payment_image_url'] . '" alt="' . $info['payment_title'] . '" />';
        $ret .= '<h3>' . $info['payment_title'] . '</h3>';
        $ret .= '<p>' . $info['payment_description'] . '</p>';
        $ret .= '</div>';

        return $ret;
    }

    /**
     * @return array
     */
    public function getCountriesList()
    {
        require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

        return XoopsLists::getCountryList();
    }

}
