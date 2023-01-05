<?php

namespace OEngine\Highlight\Formats;


class XMLFormat extends AFormat
{
    public static $Keys = ["xml", "html"];

    /**
     * @return mixed|string
     */
    public function highlight()
    {
        $text = htmlspecialchars($this->_text);
        // Brackets
        $text = preg_replace(
            '#&lt;([/]*?)(.*)([\s]*?)&gt;#sU',
            '<span style="color: ' . $this->_theme::getXMLTagColor() . '">&lt;\\1\\2\\3&gt;</span>',
            $text
        );
        // Xml version
        $text = preg_replace(
            '#&lt;([\?])(.*)([\?])&gt;#sU',
            '<span style="color: ' . $this->_theme::getXMLInfoColor() . '">&lt;\\1\\2\\3&gt;</span>',
            $text
        );
        // Attributes
        $text = preg_replace(
            "#([^\s]*?)\=(&quot;|')(.*)(&quot;|')#isU",
            '<span style="color: ' . $this->_theme::getXMLAttrNameColor() . '">\\1</span>=<span style="color: ' . $this->_theme::getXMLAttrValueColor() . '">\\2\\3\\4</span>',
            $text
        );

        return '<span style="color: ' . $this->_theme->getStringColor() . '">' . $text . '</span>';
    }
}
