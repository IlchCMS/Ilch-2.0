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
     * Returns the auth token model for the given selector or null if no token
     * with that selector exists.
     *
     * @param string $selector
     * @return AuthTokenModel|null
     */
    public function getAuthToken(string $selector): ?AuthTokenModel
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
     * Adds a new auth token to the database.
     *
     * @param AuthTokenModel $authToken
     * @return int Insert id of the new auth token row
     */
    public function addAuthToken(AuthTokenModel $authToken): int
    {
        $insert = $this->db()->insert();
        return $insert->into('auth_tokens')
            ->values(['selector' => $authToken->getSelector(), 'token' => $authToken->getToken(), 'userid' => $authToken->getUserid(), 'expires' => $authToken->getExpires()])
            ->execute();
    }

    /**
     * Updates the auth token in the database.
     *
     * @param AuthTokenModel $authToken
     * @return int Number of changed rows
     */
    public function updateAuthToken(AuthTokenModel $authToken): int
    {
        $update = $this->db()->update();
        return $update->table('auth_tokens')
            ->values(['token' => $authToken->getToken(), 'userid' => $authToken->getUserid(), 'expires' => $authToken->getExpires()])
            ->where(['selector' => $authToken->getSelector()])
            ->execute();
    }

    /**
     * Deletes the auth token with the given selector from the database.
     *
     * @param string $selector
     * @return int Number of deleted rows
     */
    public function deleteAuthToken(string $selector): int
    {
        $delete = $this->db()->delete();
        return $delete->from('auth_tokens')
            ->where(['selector' => $selector])
            ->execute();
    }

    /**
     * Deletes all auth tokens of a user from the database.
     *
     * @param int $userid
     * @return int Number of deleted rows
     */
    public function deleteAllAuthTokenOfUser(int $userid): int
    {
        $delete = $this->db()->delete();
        return $delete->from('auth_tokens')
            ->where(['userid' => $userid])
            ->execute();
    }

    /**
     * Deletes all expired auth tokens from the database.
     *
     * @return int Number of deleted rows
     */
    public function deleteExpiredAuthTokens(): int
    {
        $delete = $this->db()->delete();
        return $delete->from('auth_tokens')
            ->where(['expires <' => date('Y-m-d\TH:i:s')])
            ->execute();
    }
}
