<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Service;

use Modules\User\Mappers\AuthToken as AuthTokenMapper;
use Modules\User\Mappers\CookieStolen as CookieStolenMapper;
use Modules\User\Models\AuthToken as AuthTokenModel;

/**
 * Class for "remember me" feature
 */
class Remember
{
    /**
     * Generate and store authtoken and write "remember" cookie.
     *
     * @param int $userId
     * @throws \Exception
     * @since 2.1.38
     */
    public function rememberMe($userId)
    {
        $authTokenModel = new AuthTokenModel();

        // 9 bytes of random data (base64 encoded to 12 characters) for the selector.
        // This provides 72 bits of keyspace and therefore 236 bits of collision resistance (birthday attacks)
        $authTokenModel->setSelector(base64_encode(random_bytes(9)));
        // 33 bytes (264 bits) of randomness for the actual authenticator. This should be unpredictable in all practical scenarios.
        $authenticator = random_bytes(33);
        // SHA256 hash of the authenticator. This mitigates the risk of user impersonation following information leaks.
        $authTokenModel->setToken(hash('sha256', $authenticator));
        $authTokenModel->setUserid($userId);
        $authTokenModel->setExpires(date('Y-m-d\TH:i:s', strtotime( '+30 days' )));

        setcookieIlch('remember', $authTokenModel->getSelector().':'.base64_encode($authenticator), strtotime('+30 days'));

        $authTokenMapper = new AuthTokenMapper();
        $authTokenMapper->addAuthToken($authTokenModel);
    }

    /**
     * Reauthenticate user with the existing "remember" cookie.
     *
     * @throws \Exception
     * @since 2.1.38
     */
    public function reauthenticate()
    {
        $remember = explode(':', $_COOKIE['remember']);

        if (\count($remember) !== 2) {
            $remember[1] = '';
        }
        list($selector, $authenticator) = $remember;

        $authTokenMapper = new AuthTokenMapper();
        $authToken = $authTokenMapper->getAuthToken($selector);

        if ($authToken !== null && strtotime($authToken->getExpires()) >= time()) {
            if (hash_equals($authToken->getToken(), hash('sha256', base64_decode($authenticator)))) {
                $_SESSION['user_id'] = $authToken->getUserid();
                // A new token is generated, a new hash for the token is stored over the old record, and a new login cookie is issued to the user.
                $authTokenModel = new AuthTokenModel();

                $authTokenModel->setSelector($selector);
                // 33 bytes (264 bits) of randomness for the actual authenticator. This should be unpredictable in all practical scenarios.
                $authenticator = random_bytes(33);
                // SHA256 hash of the authenticator. This mitigates the risk of user impersonation following information leaks.
                $authTokenModel->setToken(hash('sha256', $authenticator));
                $authTokenModel->setUserid($_SESSION['user_id']);
                $authTokenModel->setExpires(date('Y-m-d\TH:i:s', strtotime( '+30 days' )));

                setcookieIlch('remember', $authTokenModel->getSelector().':'.base64_encode($authenticator), strtotime('+30 days'));

                $authTokenMapper->updateAuthToken($authTokenModel);
            } else {
                // If the series is present but the token does not match, a theft is assumed.
                // The user receives a strongly worded warning and all of the user's remembered sessions are deleted.
                $cookieStolenMapper = new CookieStolenMapper();
                $cookieStolenMapper->addCookieStolen($authToken->getUserid());
                $authTokenMapper->deleteAllAuthTokenOfUser($authToken->getUserid());
            }
        }
    }
}
