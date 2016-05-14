<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\AuthToken as AuthTokenModel;

class AuthToken extends \Ilch\Mapper
{
    public function getAuthToken($selector) {
        $select = $this->db->select('*');
        $result = $select->from('auth_tokens')
            ->where(['selector' => $selector)
            ->execute();

        if (!empty($userRows)) {
            return $result;
        }
        return null;
    }

    public function addAuthToken($authToken) {
        $insert = $this->db->insert();
        $userId = $insert->into('auth_tokens')
            ->values(['selector' => $authToken->getSelector(), 'token' => $authToken->getToken(), 'userid' => $authToken->getUserid(), 'expires' => $authToken->getExpires()])
            ->execute();
    }

    public function updateAuthToken() {
    }
}
