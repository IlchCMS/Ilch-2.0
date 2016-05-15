<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\AuthToken as AuthTokenModel;

class AuthToken extends \Ilch\Mapper
{
    /**
     * Returns the selector of the user.
     *
     * @param string selector
     * @return array
     */
    public function getAuthToken($selector) {
        $select = $this->db()->select('*');
        $result = $select->from('auth_tokens')
            ->where(['selector' => $selector])
            ->execute()
            ->fetchAssoc();

        if (!empty($result)) {
            return $result;
        }
        return null;
    }

    /**
     * Adds a new authToken to the database.
     *
     * @param string Modules\User\Models\AuthToken
     * @return int The id of the inserted authToken.
     */
    public function addAuthToken($authToken) {
        $insert = $this->db()->insert();
        return $insert->into('auth_tokens')
            ->values(['selector' => $authToken->getSelector(), 'token' => $authToken->getToken(), 'userid' => $authToken->getUserid(), 'expires' => $authToken->getExpires()])
            ->execute();
    }

    /**
     * Updates the authToken in the database.
     *
     * @param string Modules\User\Models\AuthToken
     * @return int The id of the updated authToken.
     */
    public function updateAuthToken($authToken) {
        $update = $this->db()->update();
        return $update->table('auth_tokens')
            ->values(['selector' => $authToken->getSelector(), 'token' => $authToken->getToken(), 'userid' => $authToken->getUserid(), 'expires' => $authToken->getExpires()])
            ->where(['userid' => $authToken->getUserid()])
            ->execute();
    }

    /**
     * Delete the authToken in the database.
     *
     * @param string selector
     * @return int The id of the deleted authToken.
     */
    public function deleteAuthToken($selector) {
        $delete = $this->db()->delete();
        return $delete->from('auth_tokens')
            ->where(['selector' => $selector])
            ->execute();
    }
    
    /**
     * Delete all authToken of a user in the database.
     *
     * @param int userid
     * @return int The id of the updated authToken.
     */
    public function deleteAllAuthTokenOfUser($userid) {
        $delete = $this->db()->delete();
        return $delete->from('auth_tokens')
            ->where(['userid' => $userid])
            ->execute();
    }
}
