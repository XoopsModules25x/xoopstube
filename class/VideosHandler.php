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
 *  Xoopstube class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Xoopstube
 * @subpackage      Utils
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

use XoopsModules\Xoopstube;
use XoopsModules\Xoopstube\Common;

/**
 * Class VideosHandler
 */
class VideosHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @var Helper
     * @access public
     */
    public $helper = null;

    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'xoopstube_videos', Videos::class, 'lid', 'title');
        $this->helper = Xoopstube\Helper::getInstance();
    }

    /**
     * Get criteria for active videos
     *
     * @return \CriteriaCompo
     */
    public function getActiveCriteria()
    {
        $gpermHandler = xoops_getHandler('groupperm');

        $criteria = new \CriteriaCompo(new \Criteria('offline', false));
        $criteria->add(new \Criteria('published', 0, '>'));
        $criteria->add(new \Criteria('published', time(), '<='));
        $expiredCriteria = new \CriteriaCompo(new \Criteria('expired', 0));
        $expiredCriteria->add(new \Criteria('expired', time(), '>='), 'OR');
        $criteria->add($expiredCriteria);
        // add criteria for categories that the user has permissions for
        $groups                   = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
        $allowedDownCategoriesIds = $gpermHandler->getItemIds('XTubeCatPerm', $groups, $this->helper->getModule()->mid());
        $criteria->add(new \Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'));

        return $criteria;
    }
}
