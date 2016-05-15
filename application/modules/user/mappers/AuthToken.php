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
     * @return array
     */
    public function getAuthToken($selector) {
        $select = $this->db()->select('*');
        $result = $select->from('auth_tokens')
            ->where(['selector' => $selector])
            ->execute()
            ->fetchRow();

        if (!empty($result)) {
            return $result;
        }
        return null;
    }

    /**
     * Adds a new authToken to the database.
     *
     * @return int The id of the inserted authToken.
     */
    public function addAuthToken($authToken) {
        $insert = $this->db()->insert();
        return $insert->into('auth_tokens')
            ->values(['selector' => $authToken->getSelector(), 'token' => $authToken->getToken(), 'userid' => $authToken->getUserid(), 'expires' => $authToken->getExpires()])
            ->execute();
    }

    public function updateAuthToken() {
    }
}
