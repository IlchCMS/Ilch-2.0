<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Plugins;

/**
 * Does forum operations before the controller loads.
 */
class BeforeControllerLoad
{
    /**
     * Redirects the user to the default forum page, if the user has no access.
     *
     * @param array $pluginData
     */
    public function __construct(array $pluginData)
    {
        $request = $pluginData['request'];

        if ($request->getModuleName() == 'forum') {
            if ($request->getParam('access')) {
                $translator = new \Ilch\Translator();
                $_SESSION['messages'][] = array('text' => $translator->trans('noAccessForum'), 'type' => 'danger');
            }
        }
    }
}
