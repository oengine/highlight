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
            
            <?php
            \$__highlights_option = [{$theme}];
            if(count(\$__highlights_option)==0)
            {
                \$__highlights_option = [\"\"];
            }
            
            ob_start(); ?>"; //return this if statement inside php tag
        });

        Blade::directive('endhighlight', function () {
            return "
            <?php
            \$__highlights = ob_get_clean();
            echo \OEngine\Highlight\FormatCode::Highlight(\$__highlights,...\$__highlights_option);
            ?>
            "; //return this endif statement inside php tag
        });
    }
}
