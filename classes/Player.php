<?php

class Player
{
    // TODO: add name and score
    public string $name;

    public function __construct($name)
    {
        // TODO: add 👤 automatically to their name
        $this->setName($name);
        $_SESSION['score'] = 0;
    }

    public function setName($name)
    {
        $this->name = $name . ' 👤';
        $_SESSION['username'] = $this->name;
        $_SESSION['score'] = 0;
    }
}
