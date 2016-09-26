<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class DebugBar
{
    /** @var \DebugBar\DebugBar */
    private static $debugBar;

    /**
     * @return bool
     */
    public static function init()
    {
        if (class_exists('\DebugBar\StandardDebugBar')) {
            self::$debugBar = new \DebugBar\StandardDebugBar();
            set_error_handler([__CLASS__, 'errorHandler']);
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function isInitialized()
    {
        return isset(self::$debugBar);
    }

    /**
     * @return \DebugBar\DebugBar
     * @throws \RuntimeException if debug bar is not initialized
     */
    public static function getInstance()
    {
        if (!isset(self::$debugBar)) {
            throw new \RuntimeException('DebugBar is not initialized');
        }
        return self::$debugBar;
    }

    /**
     * Error handler for displaying errors in the debug bar
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @return bool
     */
    public static function errorHandler($errno, $errstr, $errfile, $errline)
    {
        switch ($errno) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $label = 'notice';
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $label = 'warning';
                break;
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $label = 'deprecated';
                break;
            default:
                //execute PHP internal error handler for other errors
                return false;
        }

        /** @var \DebugBar\DataCollector\MessagesCollector $messageCollector */
        $messageCollector = self::$debugBar['messages'];

        $message = sprintf('%s in %s:%d', $errstr, $errfile, $errline);

        $messageCollector->addMessage($message, $label);

        //don't execute PHP internal error handler handled errors
        return true;
    }
}
