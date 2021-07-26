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

/**
 *  Session class
 *
 * @copyright       The XUUPS Project https://sourceforge.net/projects/xuups/
 * @license         https://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Xoopstube
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Harry Fuecks (PHP Anthology Volume II)
 */

use XoopsModules\Xoopstube;

require_once \dirname(__DIR__) . '/include/common.php';

/**
 * Class Session
 */
class Session
{
    /**
     * Session constructor<br>
     * Starts the session with session_start()
     * <strong>Note:</strong> that if the session has already started,
     * session_start() does nothing
     */
    protected function __construct()
    {
        if (!@\session_start()) {
            throw new \RuntimeException('Session could not start.');
        }
    }

    /**
     * Sets a session variable
     *
     * @param string $name  name of variable
     * @param mixed  $value value of variable
     *
     * @access public
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Fetches a session variable
     *
     * @param string $name name of variable
     *
     * @return mixed value of session variable
     * @access public
     */
    public function get($name)
    {
        return $_SESSION[$name] ?? false;
    }

    /**
     * Deletes a session variable
     *
     * @param string $name name of variable
     *
     * @access public
     */
    public function del($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * Destroys the whole session
     *
     * @access public
     */
    public function destroy()
    {
        $_SESSION = [];
        \session_destroy();
    }

    /**
     * @return Session
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }
}
