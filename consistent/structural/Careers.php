<?php

namespace Structural;

class Careers implements WebPage
{
    protected Theme $theme;

    public function __construct(Theme $theme)
    {
        $this->theme = $theme;
    }

    public function getContent(): string
    {
        return "Careers page in " . $this->theme->getColor();
    }
}