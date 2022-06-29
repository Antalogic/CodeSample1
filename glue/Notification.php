<?php

namespace Glue;

interface Notification
{
    public function send(string $title, string $message);
}