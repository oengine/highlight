<?php

namespace OEngine\Highlight\Formats;


class BashFormat extends AFormat
{
    public static  $Keys = ["bash"];
    /** @var string[] */
    protected $_keywords = ['wget', 'tar', 'cd', 'rsync', 'cp', 'echo', 'if', 'else', 'then', 'fi', 'while', 'echo', '=', '==', '===', 'exit', 'for', 'done', '<', '>', 'read', 'require', 'composer'];
}
