<?php

namespace Structural;

class About implements WebPage
{
    protected Theme $theme;

    public function __construct(Theme $theme)
    {
        $this->theme = $theme;
    }

    public function getContent(): string
    {
        return "About page in " . $this->theme->getColor();
    }
}