<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Code extends \JBBCode\CodeDefinition {
    public function __construct($useOption){
        parent::__construct($useOption);
        $this->setTagName('code');
        $this->setParseContent(false);
    }

    public function asHtml(\JBBCode\ElementNode $el){
        $content = $this->getContent($el);
        return '<pre class="pre-scrollable"><code>'.htmlspecialchars_decode($content).'</code></pre>';
    }
}