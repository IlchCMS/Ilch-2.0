<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\BBCode;

class CodeHelper extends \JBBCode\CodeDefinition
{
    public function __construct($useOption)
    {
        parent::__construct($useOption);
        $this->setTagName('code');
        $this->setParseContent(false);
    }

    /**
     * @param \JBBCode\ElementNode $el
     * @return string
     */
    public function asHtml(\JBBCode\ElementNode $el)
    {
        $content = $this->getContent($el);
        $content = htmlspecialchars_decode($content);
        $content = preg_replace('/\\r\\n/', '<br>', $content);

        return '<pre class="pre-scrollable"><code>'.$content.'</code></pre>';
    }
}
