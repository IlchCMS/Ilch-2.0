<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

use Modules\User\Models\User;

class Accesses
{
    /**
     * @var int
     */
    public const TYPE_MODULE = 1;
    /**
     * @var int
     */
    public const TYPE_PAGE = 2;
    /**
     * @var int
     */
    public const TYPE_BOX = 3;
    /**
     * @var int
     */
    public const TYPE_ARTICLE = 4;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var User
     */
    private $user;

    /**
     * @var array
     */
    private $accessLevel = [];


    /**
     * @param Request $request
     * Initialize all needed objects.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        if (!$this->user) {
            $user = currentUser();
            if ($user) {
                $this->setUser($user);
            }
        }

        if (!$this->user) {
            $user = new \Modules\User\Mappers\User();
            $user = $user->getDummyUser();
            $this->setUser($user);
        }

        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): Accesses
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Check access rights for modules, pages and more.
     *
     * @param string $getAccessTo Module, Admin
     * @param string|null $key
     * @param int $type
     * @return bool
     */
    public function hasAccess(string $getAccessTo = '', ?string $key = null, int $type = self::TYPE_MODULE): bool
    {
        if ($key === null) {
            $isPage = $this->request->getModuleName() === 'admin' && $this->request->getControllerName() === 'page';
            $key = $isPage ? $this->request->getParam('id', 0) : $this->request->getModuleName();
            if ($isPage) {
                $type = $this::TYPE_PAGE;
            }
        } else {
            $findSub = strpos($key, '_');
            if ($findSub !== false) {
                $keyParts = explode('_', $key);
                $key = $keyParts[1];
                $getAccessTo = $keyParts[0];
            }
        }

        $this->getUser();

        if ($getAccessTo === 'Module') {
            return $this->checkAccessUser($key, $type);
        } else {
            return $this->checkAccessAdmin(($getAccessTo === 'Admin' ? '' : $key), $type);
        }
    }

    /**
     * Gets the AccessLevel by given Param
     *
     * @param string $key A module-key, page-id or article-id prefixed by either one of these: "module_", "page_", "article_", "box_".
     * @param User|null $user
     * @return int
     * @since 2.1.51
     */
    public function getAccessLevel(string $key, ?User $user = null): int
    {
        if (!isset($this->accessLevel[$key])) {
            if ($key === 'module_admin') {
                $this->accessLevel[$key] = $this->checkAccessAdmin('', 0);
            } else {
                $groupMapper = new \Modules\User\Mappers\Group();
                $this->accessLevel[$key] = $groupMapper->getAccessLevel($key, $user ?? $this->getUser());
            }
        }

        return $this->accessLevel[$key];
    }

    /**
     * Validate AccessLevel
     *
     * @param string $key
     * @param int $type
     * @return int
     */
    private function checkAccessLevel(string $key, int $type): int
    {
        if ($type === $this::TYPE_PAGE) {
            $accessLevel = $this->getAccessLevel('page_' . $key);
        } elseif ($type === $this::TYPE_BOX) {
            $accessLevel = $this->getAccessLevel('box_' . $key);
        } elseif ($type === $this::TYPE_ARTICLE) {
            $accessLevel = $this->getAccessLevel('article_' . $key);
        } else {
            $accessLevel = $this->getAccessLevel('module_' . $key);
        }

        return $accessLevel;
    }

    /**
     * Check if user has rights to access the Modul/Page/Box.
     *
     * @param string $key
     * @param int $type
     * @return bool
     */
    private function checkAccessUser(string $key, int $type): bool
    {
        return $this->checkAccessLevel($key, $type) >= 1;
    }

    /**
     * Check if user has Admin rights to access the Modul/Page/Box.
     *
     * @param string $key
     * @param int $type
     * @return bool
     */
    private function checkAccessAdmin(string $key, int $type): bool
    {
        if ($key !== '') {
            return $this->checkAccessLevel($key, $type) === 2;
        } else {
            if ($this->getUser()->isAdmin()) {
                return true;
            }

            $groupAccessList = [];

            $groupMapper = new \Modules\User\Mappers\Group();
            foreach ($this->getUser()->getGroups() as $groups) {
                $groupAccessList[] = $groupMapper->getGroupAccessList($groups->getId());
            }

            $accessTypes = [
                $this::TYPE_MODULE => 'module',
                $this::TYPE_PAGE => 'page',
                $this::TYPE_ARTICLE => 'article',
                $this::TYPE_BOX => 'box',
            ];

            $entrie = [];
            foreach ($groupAccessList as $accessList) {
                $entries = $accessList['entries'];
                foreach ($accessTypes as $typeid => $accessType) {
                    if ($type === 0 || $type === $typeid) {
                        foreach ($entries[$accessType] as $key => $Access) {
                            if ($typeid !== $this::TYPE_MODULE) {
                                $key = $accessType . '_' . $key;
                            }
                            if (!isset($entrie[$key]) || (int)$Access > (int)$entrie[$key]) {
                                $entrie[$key] = $Access;
                            }
                        }
                    }
                }
            }
            return in_array('2', $entrie);
        }
    }

    /**
     * @param string $text
     * @return string
     */
    public function getErrorPage(string $text = ''): string
    {
        return '<div class="centering text-center error-container">
                    <div class="text-center">
                        <h2 class="without-margin"><span class="text-warning">403</span> Access denied.</h2>
                        <h4 class="text-warning">' . $text . '</h4>
                    </div>
                 </div>';
    }
}
