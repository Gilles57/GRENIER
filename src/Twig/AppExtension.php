<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('gras', [$this, 'mettre_en_gras'],
                ['is_safe' => ['html']
                ]),
            new TwigFilter('italic', [$this, 'mettre_en_italique'],
                ['is_safe' => ['html']
                ]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function mettre_en_gras($value)
    {
        return '<strong>'.$value.'</strong>';
    }


    public function mettre_en_italique($value)
    {
        return '<i>'.$value.'</i>';
    }
}
