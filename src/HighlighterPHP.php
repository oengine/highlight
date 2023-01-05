<?php

namespace OEngine\Highlight;

class HighlighterPHP extends HighlighterBase
{
    /** @var self */
    private static $_instance;

    public static function getInstance(string $text) : self
    {
        $text = str_replace('&lt;?php', '<?php', $text);
        self::setText($text);
        if (self::$_instance) {
            return self::$_instance;
        }

        return self::$_instance = new self($text);
    }

    /**
     * @return mixed
     */
    public function highlight()
    {
        $text = str_replace(['&lt;?php&nbsp;', '<code>', '</code>'], '', highlight_string('<?php ' . trim(self::$_text), true));
        $text = str_replace(PHP_EOL, '<br />', $text);

        $by_lines = explode('<br />', $text);
        $lines    = [];
        $i        = 0;
        foreach ($by_lines as $key => $line) {
            $i++;
            if ($i === 1) {
                continue;
            }
            // Join first two rows
            if ($i === 2) {
                $line = $by_lines[0] . $by_lines[1];
            }
            // Join last row
            if ($i === count($by_lines)-1) {
                $lines[] = $by_lines[$i] . $by_lines[count($by_lines)-1];
                break;
            }
            $lines[$key] = $line;
        }

        return implode('<br />', $lines);
    }
}
