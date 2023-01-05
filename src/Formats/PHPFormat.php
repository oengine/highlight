<?php

namespace OEngine\Highlight\Formats;

class PHPFormat extends AFormat
{
    public static  $Keys = ["php"];
    /**
     * @return mixed
     */
    public function highlight()
    {
        $text = str_replace(['&lt;?php&nbsp;', '<code>', '</code>'], '', highlight_string('<?php ' . trim($this->_text), true));
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
            if ($i === count($by_lines) - 1) {
                $lines[] = $by_lines[$i] . $by_lines[count($by_lines) - 1];
                break;
            }
            $lines[$key] = $line;
        }

        return implode('<br />', $lines);
    }
}
