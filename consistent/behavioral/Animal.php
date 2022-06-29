<?php

namespace Behavioral;

interface Animal
{
    public function accept(AnimalOperation $operation);
}