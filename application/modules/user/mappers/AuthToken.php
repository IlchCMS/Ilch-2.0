<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\AuthToken as AuthTokenModel;

class AuthToken extends \Ilch\Mapper
{
    /**
     * Returns the row of the user.
     *
     * @param string $selector
     * @return AuthTokenModel|null
     */
    public function getAuthToken($selector)
    {
        $select = $this->db()->select('*');
        $result = $select->from('auth_tokens')
            ->where(['selector' => $selector])
            ->execute()
            ->fetchAssoc();

        if (!empty($result)) {
            $authTokenModel = new AuthTokenModel();
            $authTokenModel->setUserid($result['userid']);
            $authTokenModel->setSelector($result['selector']);
            $authTokenModel->setToken($result['token']);
            $authTokenModel->setExpires($result['expires']);
            return $authTokenModel;
        }
        return null;
    }

    /**
     * Adds a new authToken to the database.
     *
     * @param AuthTokenModel $authToken
     * @return int
     */
    public function addAuthToken($authToken)
    {
        $insert = $this->db()->insert();
        return $insert->into('auth_tokens')
            ->values(['selector' => $authToken->getSelector(), 'token' => $authToken->getToken(), 'userid' => $authToken->getUserid(), 'expires' => $authToken->getExpires()])
            ->execute();
    }

    /**
     * Updates the authToken in the database.
     *
     * @param string Modules\User\Models\AuthToken
     * @return \Ilch\Database\Mysql\Result|int number of changed rows
     */
    public function updateAuthToken($authToken)
    {
        $update = $this->db()->update();
        return $update->table('auth_tokens')
            ->values(['token' => $authToken->getToken(), 'userid' => $authToken->getUserid(), 'expires' => $authToken->getExpires()])
            ->where(['selector' => $authToken->getSelector()])
            ->execute();
    }

    /**
     * Delete the authToken in the database.
     *
     * @param string $selector
     * @return \Ilch\Database\Mysql\Result|int number of deleted rows
     */
    public function deleteAuthToken($selector)
    {
        $delete = $this->db()->delete();
        return $delete->from('auth_tokens')
            ->where(['selector' => $selector])
            ->execute();
    }

    /**
     * Delete all authToken of a user in the database.
     *
     * @param int $userid
     * @return \Ilch\Database\Mysql\Result|int number of deleted rows
     */
    public function deleteAllAuthTokenOfUser($userid)
    {
        $delete = $this->db()->delete();
        return $delete->from('auth_tokens')
            ->where(['userid' => $userid])
            ->execute();
    }

    /**
     * Delete all expired authTokens in the database.
     *
     * @return \Ilch\Database\Mysql\Result|int number of deleted rows
     */
    public function deleteExpiredAuthTokens()
    {
        $delete = $this->db()->delete();
        return $delete->from('auth_tokens')
            ->where(['expires <' => date('Y-m-d\TH:i:s')])
            ->execute();
    }
}
