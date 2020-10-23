<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Accesses
{
    /**
     * @var
     */
    private $groupIds;

    /**
     * @var Request
     */
    private $request;

    /**
     * Initialize all needed objects.
     */
    public function __construct(\Ilch\Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    private function getGroupIds()
    {
        return $this->groupIds;
    }

    /**
     * @param $id
     */
    private function setGroupIds($id)
    {
        $this->groupIds = $id;
    }

    /**
     * Check access rights for modules, pages and more.
     *
     * @todo expansion and more functions
     * @param $getAccessTo string Module, Admin
     * @return bool
     */
    public function hasAccess($getAccessTo = '')
    {
        $userId = '';
        $groupAccessList = [];
        $groupIds = [3];

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        }

        $userMapper = new \Modules\User\Mappers\User();
        $groupMapper = new \Modules\User\Mappers\Group();

        if ($userId) {
            $user = $userMapper->getUserById($userId);

            if ($user !== null) {
                $groupIds = [];
                foreach ($user->getGroups() as $groups) {
                    $groupIds[] = $groups->getId();
                    $groupAccessList[] = $groupMapper->getGroupAccessList($groups->getId());
                }
            } else {
                // User doesn't exist anymore. "Downgrade" to guest.
                $groupAccessList[] = $groupMapper->getGroupAccessList('3');
            }
        } else {
            $groupAccessList[] = $groupMapper->getGroupAccessList('3');
        }

        $this->setGroupIds($groupIds);

        if ($getAccessTo === 'Module') {
            $getAccessTo = $this->getAccessModule($groupAccessList);
        } else {
            $getAccessTo = $this->getAccessAdmin($groupAccessList);
        }
        return $getAccessTo;
    }

    /**
     * Check if user has the rights to access the module.
     *
     * @param $array
     * @return bool
     */
    private function getAccessModule($array)
    {
        foreach ($array as $kay => $value) {
            $entries[] = $value['entries'];
            foreach ($entries as $value) {
                foreach ($value['module'] as $key => $Access) {
                    if (!isset($entrie[$key]) || (int)$Access > (int)$entrie[$key]) {
                        $entrie[$key] = $Access;
                    }
                }
            }
        }

        if (!isset($entrie[$this->request->getModuleName()]) || empty($entrie) || $this->request->getModuleName() === 'admin') {
            if ($this->request->getControllerName() === 'page') {
                return $this->getAccessPage($array);
            }

            return true;
        }

        return ($entrie[$this->request->getModuleName()] == '1' || $entrie[$this->request->getModuleName()] == '2' || is_in_array($this->getGroupIds(), ['1']));
    }

    /**
     * Check if user has the rights to access the page.
     *
     * @param $array
     * @return bool
     */
    private function getAccessPage($array)
    {
        $entrie = [];
        foreach ($array as $kay => $value) {
            $entries[] = $value['entries'];
            foreach ($entries as $value) {
                $entrie[] = $value['page'];
            }
        }

        if (is_in_array($this->getGroupIds(), ['1'])) {
            return true;
        }

        return in_array('1', array_column($entrie, (int)$this->request->getParam('id'))) || in_array('2', array_column($entrie, (int)$this->request->getParam('id')));
    }

    /**
     * @param $array
     * @return bool
     */
    private function getAccessAdmin($array)
    {
        $entrie = [];
        foreach ($array as $kay => $value) {
            $entries[] = $value['entries'];
            foreach ($entries as $value) {
                foreach ($value['module'] as $key => $Access) {
                    if (!isset($entrie[$key]) || (int)$Access > (int)$entrie[$key]) {
                        $entrie[$key] = $Access;
                    }
                }
            }
        }

        return in_array('2', $entrie);
    }

    /**
     * @param string $text
     * @return string
     */
    public function getErrorPage($text = '')
    {
        return '<div class="centering text-center error-container">
                    <div class="text-center">
                        <h2 class="without-margin"><span class="text-warning">403</span> Access denied.</h2>
                        <h4 class="text-warning">'.$text.'</h4>
                    </div>
                 </div>';
    }
}
