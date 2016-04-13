<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Accesses
{
    private $groupIds;

    private $request;

    /**
     * Initialize all needed objects.
     */
    public function __construct(\Ilch\Request $request)
    {
        $this->request = $request;
    }

    public function getGroupIds()
    {
        return $this->groupIds;
    }

    public function setGroupIds($id)
    {
        $this->groupIds = $id;
    }

    /**
     * @todo expansion and more functions
     */
    public function hasAccess()
    {
        $userId = '';
        $user = '';
        $groupAccessList = '';
        $groupIds = array(0);

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        } else {
            $userId = '3';
        }

        $userMapper = new \Modules\User\Mappers\User();
        $groupMapper = new \Modules\User\Mappers\Group();

        if ($userId != '3') {
            $user = $userMapper->getUserById($userId);

            $groupIds = array();
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
                $groupAccessList[] = $groupMapper->getGroupAccessList($groups->getId());
            }
        } else {
            $groupAccessList[] = $groupMapper->getGroupAccessList('3');
        }

        $this->setGroupIds($groupIds);
        return $this->getAccessModule($groupAccessList);
    }

    public function getAccessModule($array) {
        
        foreach ($array as $kay => $value) {
            $entries[] = $value['entries'];
            foreach ($entries as $value) {
                $entrie = $value['module'];
            }
        }

        if ($this->request->getModuleName() == 'admin' || empty($entrie)) {
            return true;
        }

        if ($entrie[$this->request->getModuleName()] == '1' || is_in_array($this->getGroupIds(), array('1')) == 'true') {
            return true;
        }
    }
}
