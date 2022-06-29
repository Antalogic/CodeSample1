<?php

namespace Abstraction;

class LinkedInPoster extends SocialNetworkPoster
{
    private string $password;
    private string $email;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getSocialNetwork(): SocialNetworkConnector
    {
        return new LinkedInConnector($this->email, $this->password);
    }
}
