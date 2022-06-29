<?php

class Client
{
    private int $id;

    /**
     * @var Email
     */
    private Email $email;

    protected function __construct($id, Email $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public static function register($id, Email $email): Client
    {
        $client = new Client($id, $email);
        $client->record(new ClientRegistered($client->id));

        return $client;
    }
}