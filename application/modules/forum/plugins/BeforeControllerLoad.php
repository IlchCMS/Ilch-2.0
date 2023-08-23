<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Plugins;

use Ilch\Translator;

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

        if (($request->getModuleName() === 'forum') && $request->getParam('access')) {
            $translator = new Translator();
            $_SESSION['messages'][] = ['text' => $translator->trans('noAccessForum'), 'type' => 'danger'];
        }
    }
}
