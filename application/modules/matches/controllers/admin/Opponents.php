<?php
/**
 * Matches opponents controller
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Matches\Controllers\Admin;

use \Ilch\Validation;

use \Modules\Matches\Models\Opponent;
use \Modules\Matches\Mappers\Opponent as OpponentMapper;

class Opponents extends Base
{
    public function indexAction()
    {

    }

    public function newAction()
    {
        // Populate the horizontal menu
        $this->getLayout()->getAdminHmenu()
            ->add(
                $this->getTranslator()->trans('hMenu.matches'),
                ['controller' => 'index', 'action' => 'index']
            )
            ->add(
                $this->getTranslator()->trans('hMenu.opponents'),
                ['controller' => 'opponents', 'action' => 'index']
            )
            ->add(
                $this->getTranslator()->trans('hMenu.new'),
                ['controller' => 'opponents', 'action' => 'index']
            );

        // Prepare an array with all valid fields a user can submit
        $userInput = [
            'name'          => $this->input('name', ''),
            'short_name'    => $this->input('short_name', ''),
            'website'       => $this->input('website', ''),
        ];

        if ($this->getRequest()->isPost()) {
            // Form fields and translations do not match, so we tell the validator which is the right translation
            Validation::setCustomFieldAliases([
                'name'          => 'opponents.name',
                'short_name'    => 'opponents.short_name',
                'website'       => 'opponents.website',
            ]);

            if (!empty($userInput['website'])) {
                $userInput['website'] = 'http://'.$userInput['website'];
            }

            // Create the validation instance
            $validation = Validation::create($userInput, [
                'name'          => 'required|length,min:2',
                'short_name'    => 'required|length,max:10',
                'website'       => 'url',
            ]);

            // Check if user input was valid
            if ($validation->isValid()) {
                try {
                    $opponent = new Opponent();
                    $opponent->setName($userInput['name'])
                        ->setShortName($userInput['short_name'])
                        ->setWebsite($userInput['website']);

                    $mapper = new OpponentMapper();
                    $mapper->save($opponent);

                    $this->addMessage('opponents.createdSuccessfully');
                    $this->redirect(['action' => 'index']);
                } catch (\Exception $e) {
                    // TODO: Log dat.
                    $this->addMessage('opponents.couldntSave', 'danger');
                }
            }

            $userInput['website'] = substr($userInput['website'], 7);

            $this->getView()->set('errors', $validation->getErrors($this->getTranslator()));
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('userInput', $userInput);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
    }

    public function mediaQueryAction()
    {
        $mapper = new \Modules\Media\Mappers\Media();
        $media = $mapper->getMediaList();

        $aMedia = [];

        foreach ($media as $image) {
            $aMedia[] = [
                'id'        => $image->getId(),
                'url'       => $image->getUrl(),
                'thumb'     => $image->getUrlThumb(),
                'name'      => $image->getName(),
                'date'      => $image->getDatetime(),
                'ext'       => $image->getEnding(),
                'catId'     => $image->getCatId(),
                'catName'   => $image->getCatName()
            ];
        }

        echo json_encode($aMedia);

//        $entryModel->setId($medias['id']);
//        $entryModel->setUrl($medias['url']);
//        $entryModel->setUrlThumb($medias['url_thumb']);
//        $entryModel->setName($medias['name']);
//        $entryModel->setDatetime($medias['datetime']);
//        $entryModel->setEnding($medias['ending']);
//        $entryModel->setCatName(($medias['cat_name']));
//        $entryModel->setCatId(($medias['cat']));
    }
}
