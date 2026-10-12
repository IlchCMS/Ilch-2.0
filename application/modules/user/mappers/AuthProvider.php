<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\AuthProviderUser;
use Modules\User\Models\Provider;
use Modules\User\Models\AuthProviderModule;

class AuthProvider extends \Ilch\Mapper
{
    protected string $default_table = 'users_auth_providers';

    public function getProviders($active = true): array
    {
        $providers = [];

        $result = $this->db()->select()
            ->fields(['p.key', 'p.name', 'p.icon', 'p.module'])
            ->from(['p' => 'auth_providers'])
            ->join(['m' => 'auth_providers_modules'], ['p.key = m.provider', 'p.module = m.module'], 'LEFT', [
                'm.auth_controller',
                'm.auth_action',
                'm.unlink_controller',
                'm.unlink_action'
            ])
            ->join(
                ['mc' => 'modules_content'],
                ['mc.key = p.module', 'mc.locale' => \Ilch\Registry::get('translator')->getLocale()],
                'LEFT',
                ['module_name' => 'mc.name']
            );

        if ($active === true) {
            $result->where(['p.module !=' => '']);
        }

        $result = $result->order(['p.name' => 'ASC'])
            ->execute();

        while ($obj = $result->fetchObject(Provider::class, [])) {
            $providers[] = $obj;
        }

        return $providers;
    }

    public function getProvider($key): ?object
    {
        $result = $this->db()->select()
            ->fields(['key', 'name', 'icon', 'module'])
            ->from('auth_providers')
            ->where(['key' => $key])
            ->execute();

        return $result->fetchObject(Provider::class, []);
    }

    public function getProviderModulesByProvider($provider): array
    {
        $providers = [];

        $result = $this->db()->select()
            ->fields(['pm.provider', 'pm.module', 'pm.auth_controller', 'pm.auth_action', 'pm.unlink_controller', 'pm.unlink_action'])
            ->from(['pm' => 'auth_providers_modules'])
            ->join(
                ['m' => 'modules_content'],
                ['pm.module = m.key', 'm.locale' => \Ilch\Registry::get('translator')->getLocale()],
                'LEFT',
                ['m.name']
            )
            ->where(['provider' => $provider])
            ->execute();

        while ($obj = $result->fetchObject(AuthProviderModule::class, [])) {
            $providers[] = $obj;
        }

        return $providers;
    }

    public function updateModule($provider, $module): bool
    {
        $providerData = $this->getProvider($provider);

        if ($providerData === null) {
            return false;
        }

        // MySQL reports 0 affected rows when a column is set to its current
        // value, so an unchanged value still counts as a successful update.
        if ($providerData->getModule() === $module) {
            return true;
        }

        $result = $this->db()->update(
            'auth_providers',
            ['module' => $module],
            ['key' => $provider]
        )->execute();

        return $result === 1;
    }

    public function providerAccountIsLinked($provider, $identifier): bool
    {
        $result = $this->db()->select()
            ->from($this->default_table)
            ->where(['provider' => $provider])
            ->andWhere(['identifier' => $identifier])
            ->execute();

        return $result->getNumRows() > 0;
    }

    public function hasProviderLinked($provider, $user_id): bool
    {
        $result = $this->db()->select()
            ->from($this->default_table)
            ->where([
                'user_id' => $user_id,
                'provider' => $provider
            ])
            ->execute();

        return $result->getNumRows() > 0;
    }

    public function linkProviderWithUser(AuthProviderUser $user): bool
    {
        $userId = $user->getUserId();
        $provider = $user->getProvider();
        $identifier = $user->getIdentifier();

        // Reject duplicates up front instead of swallowing every exception,
        // so that unexpected database errors are not hidden.
        if (
            $this->hasProviderLinked($provider, $userId)
            || $this->providerAccountIsLinked($provider, $identifier)
        ) {
            return false;
        }

        $this->db()->insert($this->default_table)
            ->values([
                'user_id' => $userId,
                'provider' => $provider,
                'identifier' => $identifier,
                'oauth_token' => $user->getOauthToken(),
                'oauth_token_secret' => $user->getOauthTokenSecret(),
                'screen_name' => $user->getScreenName(),
                'created_at' => (new \Ilch\Date('NOW'))->toDb(true),
            ])
            ->execute();

        return true;
    }

    public function getUserIdByProvider($provider, $identifier): bool|string|null
    {
        return $this->db()->select()
            ->fields('user_id')
            ->from($this->default_table)
            ->where([
                'identifier' => $identifier,
                'provider' => $provider
            ])
            ->order(['user_id' => 'ASC'])
            ->limit(1)
            ->execute()
            ->fetchCell('user_id');
    }

    public function getLinkedProviderDetails($provider, $user_id): ?object
    {
        return $this->db()->select()
            ->fields(['user_id', 'provider', 'screen_name', 'identifier', 'oauth_token', 'oauth_token_secret', 'created_at'])
            ->from($this->default_table)
            ->where([
                'user_id' => $user_id,
                'provider' => $provider
            ])
            ->limit(1)
            ->execute()
            ->fetchObject(AuthProviderUser::class, []);
    }

    /**
     * @param string $provider
     * @param int $user_id
     * @return int Number of affected rows
     */
    public function unlinkUser(string $provider, int $user_id): int
    {
        return $this->db()->delete()
            ->from($this->default_table)
            ->where([
                'user_id' => $user_id,
                'provider' => $provider,
            ])
            ->execute();
    }

    /**
     * Delete user by id.
     * Call this function if the user for example gets deleted and therefore
     * the links to the auth providers of that deleted user are of no use anymore.
     *
     * @param int $user_id
     * @return int Number of affected rows
     */
    public function deleteUser(int $user_id): int
    {
        return $this->db()->delete()
            ->from($this->default_table)
            ->where(['user_id' => $user_id])
            ->execute();
    }

    public function authProvidersModuleExistsForProvider($provider, $module): bool
    {
        if (empty($module)) {
            return true;
        }

        $result = $this->db()->select()
            ->from('auth_providers_modules')
            ->where(['provider' => $provider, 'module' => $module])
            ->execute();

        return $result->getNumRows() > 0;
    }

    public function deleteProvider($key): bool
    {
        $provider = $this->db()->delete('auth_providers', ['key' => $key])->execute();
        $this->db()->delete('users_auth_providers', ['provider' => $key])->execute();

        return $provider === 1;
    }
}
