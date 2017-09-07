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
 *  Xoopstube class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Xoopstube
 * @subpackage      Utils
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */
// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/**
 * Class XoopstubeXoopstube
 */
class XoopstubeXoopstube
{
    public $dirname;
    public $module;
    public $handler;
    public $config;
    public $debug;
    public $debugArray = [];

    /**
     * @param $debug
     */
    protected function __construct($debug)
    {
        $this->debug   = $debug;
        $this->dirname = basename(dirname(__DIR__));
    }

    /**
     * @param bool $debug
     *
     * @return XoopstubeXoopstube
     */
    public static function getInstance($debug = false)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($debug);
        }
        //error_log("istance: [" . print_r($istance,true) . "]");
        //phpinfo();
        //debug_print_backtrace ();
        return $instance;
    }

    /**
     * @return null
     */
    public function getModule()
    {
        if (null === $this->module) {
            $this->initModule();
        }

        return $this->module;
    }

    /**
     * @param null $name
     *
     * @return null
     */
    public function getConfig($name = null)
    {
        if (null === $this->config) {
            $this->initConfig();
        }
        if (!$name) {
            $this->addLog('Getting all config');

            return $this->config;
        }
        if (!isset($this->config[$name])) {
            $this->addLog("ERROR :: CONFIG '{$name}' does not exist");

            return null;
        }
        $this->addLog("Getting config '{$name}' : " . print_r($this->config[$name], true));

        return $this->config[$name];
    }

    /**
     * @param null $name
     * @param null $value
     *
     * @return mixed
     */
    public function setConfig($name = null, $value = null)
    {
        if (null === $this->config) {
            $this->initConfig();
        }
        $this->config[$name] = $value;
        $this->addLog("Setting config '{$name}' : " . $this->config[$name]);

        return $this->config[$name];
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getHandler($name)
    {
        if (!isset($this->handler[$name . 'Handler'])) {
            $this->initHandler($name);
        }
        $this->addLog("Getting handler '{$name}'");

        return $this->handler[$name . 'Handler'];
    }

    public function initModule()
    {
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') === $this->dirname) {
            $this->module = $xoopsModule;
        } else {
            $hModule      = xoops_getHandler('module');
            $this->module = $hModule->getByDirname($this->dirname);
        }
        $this->addLog('INIT MODULE');
    }

    public function initConfig()
    {
        $this->addLog('INIT CONFIG');
        $hModConfig   = xoops_getHandler('config');
        $this->config = $hModConfig->getConfigsByCat(0, $this->getModule()->getVar('mid'));
    }

    /**
     * @param $name
     */
    public function initHandler($name)
    {
        $this->addLog('INIT ' . $name . ' HANDLER');
        $this->handler[$name . 'Handler'] = xoops_getModuleHandler($name, $this->dirname);
    }

    /**
     * @param $log
     */
    public function addLog($log)
    {
        if ($this->debug && is_object($GLOBALS['xoopsLogger'])) {
            $GLOBALS['xoopsLogger']->addExtra($this->module->name(), $log);
        }
    }
}

/**
 * Class XoopstubeXoopstubeHandler
 */
class XoopstubeXoopstubeHandler extends XoopsPersistableObjectHandler
{
    /**
     * @var XoopstubeXoopstube
     * @access public
     */
    public $xoopstube = null;

    /**
     * @param null|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'xoopstube_videos', 'XoopstubeXoopstube', 'lid', 'title');
        $this->xoopstube = XoopstubeXoopstube::getInstance();
    }

    /**
     * Get criteria for active videos
     *
     * @return CriteriaElement
     */
    public function getActiveCriteria()
    {
        $gpermHandler = xoops_getHandler('groupperm');

        $criteria = new CriteriaCompo(new Criteria('offline', false));
        $criteria->add(new Criteria('published', 0, '>'));
        $criteria->add(new Criteria('published', time(), '<='));
        $expiredCriteria = new CriteriaCompo(new Criteria('expired', 0));
        $expiredCriteria->add(new Criteria('expired', time(), '>='), 'OR');
        $criteria->add($expiredCriteria);
        // add criteria for categories that the user has permissions for
        $groups                   = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
        $allowedDownCategoriesIds = $gpermHandler->getItemIds('XTubeCatPerm', $groups, $this->xoopstube->getModule()->mid());
        $criteria->add(new Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'));

        return $criteria;
    }
}
