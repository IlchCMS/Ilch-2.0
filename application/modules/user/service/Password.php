<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Service;

/**
 * Klasse als Wrapper für Passwort Hash Logik
 */
class Password
{
    /** @var int */
    private $algorithm;

    /**
     * Password constructor
     * @param int|null $algorithm if not set the default for the php version will be used
     */
    public function __construct($algorithm = null)
    {
        if (version_compare(PHP_VERSION, '5.5.0', '<')) {
            require_once APPLICATION_PATH . '/libraries/password_compat/password.php';
        }
        if (null === $algorithm) {
            $algorithm = PASSWORD_DEFAULT;
        }
        $this->algorithm = $algorithm;
    }

    /**
     * @param string $password plaintext password
     * @return string hashed password
     * @throws \RuntimeException
     */
    public function hash($password)
    {
        $hash = password_hash($password, $this->algorithm);
        if (false === $hash) {
            throw new \RuntimeException('Password could not be hashed');
        }
        return $hash;
    }

    /**
     * @param string $password plaintext password
     * @param string $hash password hash
     * @return bool
     */
    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Generates a secure password
     * @param int $length
     * @param string|null $keyspace
     * @return string
     */
    public static function generateSecurePassword($length = 16, $keyspace = null)
    {
        if (is_null($keyspace)) {
            $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ`-=~!@#$%^&*()_+,./<>?;:[]{}\|";
        }
        $str = '';
        $max = strlen($keyspace) - 1;
        if ($max < 1) {
            throw new \Exception('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
}
