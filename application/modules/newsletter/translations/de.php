<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

return array
(
    'menuNewsletter' => 'Newsletter',
    'subject' => 'Betreff',
    'date' => 'Datum',
    'text' => 'Text',
    'from' => 'von',
    'noNewsletter' => 'Keine Newsletter vorhanden.',
    'missingSubject' => 'Betreff muss ausgefüllt werden',
    'missingText' => 'Text muss ausgefüllt werden',
    'sendSuccess' => 'Newsletter erfolgreich versendet',
    'show' => 'Anschauen',
    'add' => 'Schreiben',
    'send' => 'Absenden',
    'entry' => 'Ein- / Austragen',
    'email' => 'E-Mail',
    'noEmails' => 'Keine E-Mail Adressen vorhanden',
    'subscribeSuccess' => 'E-Mail wurde erfolgreich eingetragen.',
    'unsubscribeSuccess' => 'E-Mail wurde erfolgreich ausgetragen.',
    'noReplyMailFooter' => 'Bitte antworten Sie nicht auf diese E-Mail. Dieses Postfach wird nicht überwacht, deshalb werden Sie keine Antwort erhalten.',
    'mailUnreadable' => 'Falls Sie keine HTML E-Mail lesen können klicken Sie <a href="'.BASE_URL.'/index.php/newsletter/index/show/id/%s/email/%s">hier</a>.',
    'mailUnsubscribe' => 'Wenn Sie keine Newsletter von uns erhalten wollen, können Sie diese jederzeit <a href="'.BASE_URL.'/index.php/newsletter/index/unsubscribe/email/%s">hier</a> abmelden.',
);
