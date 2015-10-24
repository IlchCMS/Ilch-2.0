<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Guestbook\Controllers;

use Modules\Guestbook\Mappers\Guestbook as GuestbookMapper;
use Ilch\Date as IlchDate;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('guestbook'), array('action' => 'index'));
        $guestbookMapper = new GuestbookMapper();
        $pagination = new \Ilch\Pagination();
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('entries', $guestbookMapper->getEntries(array('setfree' => 1), $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function newEntryAction()
    {
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('guestbook'), array('action' => 'index'))
            ->add($this->getTranslator()->trans('entry'), array('action' => 'newentry'));

        $guestbookMapper = new GuestbookMapper();
        $ilchdate = new IlchDate;

        $post = [
            'name'      => '',
            'email'     => '',
            'text'      => '',
            'homepage'  => ''
        ];

        if ($this->getRequest()->isPost() and ($this->getRequest()->getPost('bot') === '')) {
            $post = [
                'name'      => $this->getRequest()->getPost('name'),
                'email'     => trim($this->getRequest()->getPost('email')),
                'text'      => trim($this->getRequest()->getPost('text')),
                'homepage'  => trim($this->getRequest()->getPost('homepage')),
                'captcha'   => trim($this->getRequest()->getPost('captcha')),
            ];

            /*
                Selbstverständlich können auch eigene Validatoren hinzugefügt werden. Hier
                gibt es zwei Möglichkeiten:

                Möglichkeit 1: Eine Closure oder auch anonyme Funktion
                    Zu beachten ist hier, dass der Name des Validators nicht bereits
                    vorhanden sein darf, 'same' würde eine Exception werfen!

                    Ansonsten wird immer der Parameter $data übergeben, in dem am Ende alle
                    Informationen stecken, die der Validator braucht.

                    $data->getValue()  -> Der Wert, der durch den Benutzer im Formular eingetragen wurde
                    $data->getInput()  -> Alle Daten, die durch den Benutzer eingetragen wurden
                    $data->getParam()  -> Hier koennen die an den Validator übergebenen Parameter gefunden werden
                    $data->getParams() -> Gibt einfach das komplette Array an Parametern zurück

                    Zurückgeben muss diese Closure nun immer ein Array mit mindestens dem Ergebnis der Validation ('result')
                    und dem Übersetzungsschlüssel für die Fehlermeldung ('error_key'). Alternativ können noch
                    weitere Parameter für die Fehlermeldung übergeben werden ('error_params'). Soll ein Parameter
                    Übersetzt werden, muss er als Array übergeben werden, wo zunächst der der Übersetzungsschlüssel und dann
                    ein true hineingehört ['key', true].
                    Siehe Beispiel, hier soll der Name des anderen Feldes ebenfalls übersetzt werden.

                Möglichkeit 2: Eine eigene Validatorklasse, die von \Ilch\Validation\Validators\Base erben sollte
                    Validation::addValidator('same2', '\my\namespace\Validators\Same');
                    Als Vergleich können die bestehenden Validators genommen werden.
            */
            Validation::addValidator('same2', function($data){
                $validation = $data->getValue() == array_dot($data->getInput(), $data->getParam('as'));
                return [
                    'result' => $validation,
                    'error_key' => 'validation.errors.differentValues',
                    'error_params' => [[$data->getParam('as'), true]]
                ];
            });

            /*
                Standardmäßig werden alle Feldnamen automatisch übersetzt.
                D.h. wenn ein Input-Feld mit name="email" existiert,
                dann wird bei der Generierung der Fehlermeldungen eine
                Übersetzung mit dem Schlüssel 'email' gesucht.

                In den meisten Fällen sollte das kein Problem sein. Falls doch,
                können mit Validation::setCustomFieldAliases() aliase festgelegt werden,
                nach denen stattdessen gesucht werden soll.

                Als Beispiel dient in diesem Fall das Feld 'homepage'. Das Feld hat den Namen
                'homepage', bei der Übersetzung wurde jedoch der Schlüssel 'page' gewählt.
            */
            Validation::setCustomFieldAliases([
                'homepage'  => 'page',
            ]);

            /*
                Validation rules werden als ein Array übergeben und sind immer im Format wie
                unten zu sehen.

                Bei multidimensionalen Arrays wird ebenfalls die Punktnotation unterstützt,
                d.h. um an $post['foo']['bar'] zu kommen, muss einfach nur 'foo.bar' als
                Schlüssel gesetzt werden.

                Die einzelnen Validatoren werden mit | getrennt. Weitere Parameter werden mit einem
                Komma getrennt, während Schlüssel und Wert mit einem Doppelpunkt getrennt werden, Beispiel:
                    'name'      => 'required|length,max:16,min:3',

                Um für eine Validation rule eine eigene Fehlermeldung anzugeben, die alle vom Validator
                selbst generierten Fehlermeldungen überschreibt, kann jeder Validation rule ein
                customErrorAlias:translation.key als Parameter mitgegeben werden. Siehe Beispiel:
                    'email'     => 'required,customErrorAlias:unsupportedEmailAddress|email

                Bei Validatoren, die unterschiedliche Fehlermeldungen generieren können, können stattdessen
                auch die einzelnen, spezifischen Fehlermeldungen überschrieben werden. Dies ist dann
                allerdings immer vom genutzten Validator abhängig.
            */
            $validation = Validation::create($post, [
                'captcha'   => 'captcha',
                'name'      => 'required',
                'email'     => 'required|email',
                'text'      => 'required',
                'homepage'  => 'url',
            ]);

            /*
                Um herauszufinden, ob eine Validation nun erfolgreich war oder ob
                Fehler gefunden wurden, ist die Methode isValid nötig.
            */
            if ($validation->isValid()) {
                $model = new \Modules\Guestbook\Models\Entry();
                $model->setName($post['name']);
                $model->setEmail($post['email']);
                $model->setText($post['text']);
                $model->setHomepage($post['homepage']);
                $model->setDatetime($ilchdate->toDb());
                $model->setFree($this->getConfig()->get('gbook_autosetfree'));
                $guestbookMapper->save($model);

                if ($this->getConfig()->get('gbook_autosetfree') == 0 ) {
                    $this->addMessage('check', 'success');
                }

                unset($_SESSION['captcha']);
                $this->redirect(array('action' => 'index'));
            }
            unset($_SESSION['captcha']);

            /*
                Nun werden noch die Fehler generiert und an das View übergeben.
                $validation->getErrors() benötigt immer die aktuelle Translator-Instanz, damit
                die Fehlermeldungen übersetzt werden können.

                Zurückgegeben wird einfach nur ein Array mit Fehler-Strings
                Ansonsten siehe newEntry.php
            */
            $this->getView()->set('errors', $validation->getErrors($this->getTranslator()));

            /*
                $validation->getFieldsWithError() gibt ein Array mit allen Feldern, die Fehler beinhalten, zurück.
            */
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('post', $post);

        /*
            Um die Felder mit Fehlern dementsprechen Kennzeichnen zu können, wird noch ein Array
            mit allen Feldernamen, die Fehler beinhalten, ans View übergeben.

            Falls $errorFields nicht existiert wird einfach ein leeres Array übergeben, um eine Fehlermeldung zu vermeiden.
        */
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
    }
}
