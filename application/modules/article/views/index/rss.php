<?php
$userMapper = $this->get('userMapper');
$articles = $this->get('articles');
$date = new \Ilch\Date();

// $adminAccess = null;
// if ($this->getUser()) {
    // $adminAccess = $this->getUser()->isAdmin();
// }

$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true;

$rss = $xml->createElement('rss');
$rss->setAttribute('version', '2.0');
$xml->appendChild($rss);

$channel = $xml->createElement('channel');
$rss->appendChild($channel);

$head = $xml->createElement('title', $this->get('siteTitle'));
$channel->appendChild($head);

$head = $xml->createElement('description', $this->getTrans('rssDesc', $this->get('siteTitle')));
$channel->appendChild($head);

$head = $xml->createElement('language', substr($this->getTranslator()->getLocale(), 0, 2));
$channel->appendChild($head);

$head = $xml->createElement('link', $this->getURL());
$channel->appendChild($head);

$head = $xml->createElement('lastBuildDate', $date->format('D, j M Y H:i:s e', true));
$channel->appendChild($head);

if ($articles) {
    foreach ($articles as $article) {
        //if (!is_in_array($this->get('readAccess'), explode(',', $article->getReadAccess())) && $adminAccess == false) {
        if (!is_in_array($this->get('readAccess'), explode(',', $article->getReadAccess()))) {
            continue;
        }

        $articleDate = new \Ilch\Date($article->getDateCreated());
        $user = $userMapper->getUserById($article->getAuthorId());
        $strippedContent = str_replace('&nbsp;', ' ', $article->getContent());

        $item = $xml->createElement('item');
        $channel->appendChild($item);

        $data = $xml->createElement('title', $this->escape($article->getTitle()));
        $item->appendChild($data);

        if (strpos($strippedContent, '[PREVIEWSTOP]') !== false) {
            $contentParts = explode('[PREVIEWSTOP]', $strippedContent);
            $data = $xml->createElement('description', reset($contentParts));
            $item->appendChild($data);
        } else {
            $data = $xml->createElement('description', $strippedContent);
            $item->appendChild($data);
        }

        if ($user) {
            $data = $xml->createElement('author', $this->escape($user->getName()));
            $item->appendChild($data);
        }

        $data = $xml->createElement('link', $this->getUrl(['action' => 'show', 'id' => $article->getId()]));
        $item->appendChild($data);

        $data = $xml->createElement('pubDate', $articleDate->format('D, j M Y H:i:s e', true));
        $item->appendChild($data);

        $data = $xml->createElement('guid', $this->getUrl(['action' => 'show', 'id' => $article->getId()]));
        $item->appendChild($data);
    }
}

$xml->save('rss.xml');

Header('Location: '.$this->getURL().'rss.xml');
