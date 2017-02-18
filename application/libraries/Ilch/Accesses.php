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
    public function getGroupIds()
    {
        return $this->groupIds;
    }

    /**
     * @param $id
     */
    public function setGroupIds($id)
    {
        $this->groupIds = $id;
    }

    /**
     * @todo expansion and more functions
     * @param $getAccessTo string Module, Admin
     * @return array $groupAccessList
     */
    public function hasAccess($getAccessTo = '')
    {

        $userId = '';
        $groupAccessList = [];
        $groupIds = [0];

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        }

        $userMapper = new \Modules\User\Mappers\User();
        $groupMapper = new \Modules\User\Mappers\Group();

        if ($userId) {
            $user = $userMapper->getUserById($userId);

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
                $groupAccessList[] = $groupMapper->getGroupAccessList($groups->getId());
            }
        } else {
            $groupAccessList[] = $groupMapper->getGroupAccessList('3');
        }

        $this->setGroupIds($groupIds);

        if ($getAccessTo == 'Module') {
            $getAccessTo = $this->getAccessModule($groupAccessList);
        } elseif ($getAccessTo == 'Admin') {
            $getAccessTo = $this->getAccessAdmin($groupAccessList);
        }
        return $getAccessTo;
    }

    /**
     * @param $array
     * @return bool
     */
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

        if (!isset($entrie[$this->request->getModuleName()])) {
            return true;
        }

        if ($entrie[$this->request->getModuleName()] == '1' ||
            $entrie[$this->request->getModuleName()] == '2' ||
            is_in_array($this->getGroupIds(), ['1']) == 'true') {
            return true;
        }
    }

    /**
     * @param $array
     * @return bool
     */
    public function getAccessAdmin($array) {

        foreach ($array as $kay => $value) {
            $entries[] = $value['entries'];
            foreach ($entries as $value) {
                $entrie = $value['module'];
            }
        }

        if (in_array('2', $entrie)) {
            return true;
        }
    }

    /**
     * @param string $text
     * @return string
     */
    public function getErrorPage($text = '') {
        $html = '<div class="centering text-center error-container">
                    <div class="text-center">
                        <h2 class="without-margin"><span class="text-warning"><big>403</big></span> Access denied.</h2>
                        <h4 class="text-warning">'.$text.'</h4>
                    </div>
                 </div>';
        return $html;
    }
}
