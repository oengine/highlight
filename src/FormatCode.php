<?php

namespace OEngine\Highlight;

use OEngine\Highlight\Formats\BashFormat;
use OEngine\Highlight\Formats\PHPFormat;
use OEngine\Highlight\Formats\XMLFormat;
use OEngine\Highlight\Themes\Styles;
use OEngine\Highlight\Themes\Theme;

class FormatCode
{
    public static function Highlight(string $text, string $theme = '', bool| null $isNumberLine = null, bool| null $isActionPanel = null)
    {
        $formatCode = new FormatCode($text, $theme);
        if ($isNumberLine) {
            $formatCode->setShowLineNumbers(true);
        }
        if ($isActionPanel) {
            $formatCode->setShowActionPanel(true);
        }
        return $formatCode->parse();
    }
    /** @var string */
    protected  $_text;

    /** @var bool */
    private $_showActionPanel = true;

    /** @var bool */
    private $_showLineNumbers = false;

    /** @var Theme */
    private $_theme;

    public function __construct(string $text, string $theme = '')
    {
        $this->_text  = str_replace('<?php', '&lt;?php', $text);
        $this->_theme = new Theme($theme);
    }

    /**
     * @return string|string[]|null
     */
    public function parse()
    {
        return preg_replace_callback(
            '/<pre([^>]+)>(.*?)<\/pre>/ism',
            function ($matches) {
                preg_match_all('/data-(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?/ism', $matches[1], $attributes);
                $data = [];
                foreach ($attributes[1] as $key => $attr) {
                    $data[$attr] = $attributes[2][$key];
                }
                $block = isset($matches[2]) ? trim($matches[2]) : '';
                $lang  = $data['lang'] ?? '';
                $file  = $data['file'] ?? '';
                $theme = $data['theme'] ?? '';

                if (!$lang) {
                    return str_replace('<?php', '&lt;?php', $block);
                }

                return $this->parseBlock($block, $lang, $file, $theme);
            },
            $this->_text
        );
    }

    private function parseBlock(string $block, string $lang, string $filePath = '', string $theme = ''): string
    {
        $lang = strtolower($lang);
        if (in_array($lang, PHPFormat::$Keys)) {
            $highlighter = new PHPFormat($block);
        } elseif (in_array($lang, BashFormat::$Keys)) {
            $highlighter = new BashFormat($block);
        } elseif (in_array($lang, XMLFormat::$Keys)) {
            $highlighter = new XMLFormat($block);
        } else {
            $highlighter = new BashFormat($block);
        }
        if ($theme) {
            $highlighter->setTheme(new Theme($theme));
        } else {
            $highlighter->setTheme(new Theme($this->_theme->getName()));
        }

        $block = $highlighter->highlight();

        return $this->wrapCode($block, $this->_theme::getBackgroundColor(), $filePath);
    }

    private function wrapCode(string $text, string $bgColor = '', string $filePath = ''): string
    {
        $wrapper = '<div class="code-block-wrapper">';
        if ($this->_showActionPanel) {
            $wrapper .= '
            <div class="meta" style="' . Styles::getCodeBlockWrapperMetaStyle() . '">
                <div class="actions" style="' . Styles::getCodeBlockWrapperActionsStyle() . '">
                    <span class="js-copy-clipboard copy-text" style="' . Styles::getCodeBlockWrapperCopyTextStyle() . '" onclick="PHPHighlight.copyClipboard(this)">copy</span>
                    <span class="meta-divider" style="' . Styles::getCodeBlockWrapperMetaDividerStyle() . '"></span>
                </div>
                <div class="info" style="' . Styles::getCodeBlockWrapperInfoStyle() . '">
                    <span>' . $filePath . '</span>
                </div>
            </div>';
        }

        $line_numbers = '';
        $text         = str_replace('<br />', PHP_EOL, $text);
        if ($this->_showLineNumbers) {
            $line_numbers = $this->setLineNumbers(count(explode(PHP_EOL, $text)));
        }
        $wrapper .= '<div class="code-highlighter" style="' . Styles::getCodeHighlighterStyle() . '; background-color: ' . $bgColor . '">' . $line_numbers . '<div class="code-block">' . $text . '</div></div></div>';

        return $wrapper;
    }

    private function setLineNumbers(int $count): string
    {
        // Don't show line number if there is only one line
        if ($count === 1) {
            return false;
        }

        $line_numbers = '';
        for ($i = 1; $i < $count + 1; $i++) {
            $line_numbers .= '<span class="line-number" style="' . Styles::getLineNumberStyle() . '; color: ' . $this->_theme::getDefaultColor() . '">' . $i . '</span>';
        }

        return '<div class="line-numbers" style="' . Styles::getLineNumbersStyle() . '">' . $line_numbers . '</div>';
    }

    public function setShowActionPanel(bool $status): void
    {
        $this->_showActionPanel = $status;
    }

    public function setShowLineNumbers(bool $status): void
    {
        $this->_showLineNumbers = $status;
    }
}
