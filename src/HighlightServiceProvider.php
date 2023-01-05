<?php

namespace OEngine\Highlight;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class HighlightServiceProvider extends ServiceProvider
{
    public function register()
    {
        Blade::directive('highlight', function ($theme) {
            return "
            <?php ob_start(); ?>"; //return this if statement inside php tag
        });

        Blade::directive('endhighlight', function ($theme='html') {
            return "
            <?php
            \$__highlights = ob_get_clean();
            echo \OEngine\Highlight\FormatCode::Highlight(\$__highlights,{$theme});
            ?>
            "; //return this endif statement inside php tag
        });
    }
}
