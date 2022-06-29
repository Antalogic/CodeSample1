<?php

namespace Structural;

interface WebPage
{
    public function __construct(Theme $theme);

    public function getContent();
}