<?php

namespace XoopsModules\Xoopstube\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * LetterChoice class
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      lucio <lucio.rota@gmail.com>
 * @package     xoopstube
 * @since       1.00
 * @version     $Id:$
 *
 * Example:
 * $choicebyletter = new Xoopstube\LetterChoice($objHandler, null, null, range('a', 'z'), 'letter');
 * echo $choicebyletter->render();
 */

use CriteriaCompo;
use XoopsModules\Xoopstube;
use XoopsTpl;
use xos_opal_Theme;



// require_once  dirname(dirname(__DIR__)) . '/include/common.php';

/**
 * Class LetterChoice
 */
class LetterChoice
{
    /**
     * @access public
     */
    public $helper = null;
    /**
     * *#@+
     *
     * @access private
     */
    private $objHandler;
    private $criteria;
    private $field_name;
    private $alphabet;
    private $arg_name;
    private $url;
    private $extra;
    private $caseSensitive;
    /**
     * *#@-
     * @param mixed      $objHandler
     * @param null|mixed $criteria
     * @param null|mixed $field_name
     * @param mixed      $arg_name
     * @param null|mixed $url
     * @param mixed      $extra_arg
     * @param mixed      $caseSensitive
     */

    /**
     * Constructor
     *
     * @param \XoopsPersistableObjectHandler $objHandler {@link XoopsPersistableObjectHandler}
     * @param \CriteriaElement               $criteria   {@link CriteriaElement}
     * @param string                         $field_name search by field
     * @param array                          $alphabet   array of alphabet letters
     * @param string                         $arg_name   item on the current page
     * @param string                         $url
     * @param string                         $extra_arg  Additional arguments to pass in the URL
     * @param bool                           $caseSensitive
     */
    public function __construct(
        $objHandler,
        $criteria = null,
        $field_name = null,
        array $alphabet = [],
        $arg_name = 'letter',
        $url = null,
        $extra_arg = '',
        $caseSensitive = false
    ) {
        /** @var \Xoopstube\Helper $this ->helper */
        $this->helper     = Xoopstube\Helper::getInstance();
        $this->objHandler = $objHandler;
        $this->criteria   = $criteria ?? new CriteriaCompo();
        $this->field_name = $field_name ?? $this->objHandler->identifierName;
        //        $this->alphabet   = (count($alphabet) > 0) ? $alphabet : range('a', 'z'); // is there a way to get locale alphabet?
        //        $this->alphabet       = getLocalAlphabet();
        $this->alphabet = require_once dirname(__DIR__, 2) . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/alphabet.php';
        $this->arg_name = $arg_name;
        $this->url      = $url ?? $_SERVER['SCRIPT_NAME'];
        if ('' !== $extra_arg && ('&amp;' !== mb_substr($extra_arg, -5) || '&' !== mb_substr($extra_arg, -1))) {
            $this->extra = '&amp;' . $extra_arg;
        }
        $this->caseSensitive = $caseSensitive;
    }

    /**
     * Create choice by letter
     *
     * @param null $alphaCount
     * @param null $howmanyother
     * @return string
     */
    public function render($alphaCount = null, $howmanyother = null)
    {
        $moduleDirName      = basename(dirname(__DIR__, 2));
        $moduleDirNameUpper = mb_strtoupper($moduleDirName);
        xoops_loadLanguage('common', $moduleDirName);
        xoops_loadLanguage('alphabet', $moduleDirName);
        $all   = constant('CO_' . $moduleDirNameUpper . '_ALL');
        $other = constant('CO_' . $moduleDirNameUpper . '_OTHER');

        $ret = '';

        if (!$this->caseSensitive) {
            $this->criteria->setGroupBy('UPPER(LEFT(' . $this->field_name . ',1))');
        } else {
            $this->criteria->setGroupBy('LEFT(' . $this->field_name . ',1)');
        }
        $countsByLetters = $this->objHandler->getCounts($this->criteria);
        // fill alphabet array
        $alphabetArray = [];
        $letter_array  = [];

        $letter                 = 'All';
        $letter_array['letter'] = $all;
        $letter_array['count']  = $alphaCount;
        $letter_array['url']    = $this->url;
        $alphabetArray[$letter] = $letter_array;

        foreach ($this->alphabet as $letter) {
            $letter_array = [];
            if (!$this->caseSensitive) {
                if (isset($countsByLetters[mb_strtoupper($letter)])) {
                    $letter_array['letter'] = $letter;
                    $letter_array['count']  = $countsByLetters[mb_strtoupper($letter)];
                    $letter_array['url']    = $this->url . '?' . $this->arg_name . '=' . $letter . $this->extra;
                } else {
                    $letter_array['letter'] = $letter;
                    $letter_array['count']  = 0;
                    $letter_array['url']    = '';
                }
            } elseif (isset($countsByLetters[$letter])) {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = $countsByLetters[$letter];
                $letter_array['url']    = $this->url . '?' . $this->arg_name . '=' . $letter . $this->extra;
            } else {
                $letter_array['letter'] = $letter;
                $letter_array['count']  = 0;
                $letter_array['url']    = '';
            }
            $alphabetArray[$letter] = $letter_array;
            unset($letter_array);
        }

        $letter_array['letter'] = $other;
        $letter_array['count']  = $howmanyother;
        $letter_array['url']    = $this->url . '?init=Other';
        $alphabetArray[$letter] = $letter_array;

        // render output
        if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
            require_once $GLOBALS['xoops']->path('/class/theme.php');
            $GLOBALS['xoTheme'] = new xos_opal_Theme();
        }
        require_once $GLOBALS['xoops']->path('/class/template.php');
        $choiceByLetterTpl          = new XoopsTpl();
        $choiceByLetterTpl->caching = 0; // Disable cache
        $choiceByLetterTpl->assign('alphabet', $alphabetArray);
        $ret .= $choiceByLetterTpl->fetch("db:{$this->helper->getDirname()}_letterschoice.tpl");
        unset($choiceByLetterTpl);

        return $ret;
    }
}
