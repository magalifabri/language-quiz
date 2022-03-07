<?php

class Player
{
    // TODO: add name and score
    public string $name;
    public int $score;

    public function __construct($name)
    {
        // TODO: add 👤 automatically to their name
        $this->setName($name);
        $this->score = 0;
    }

    public function setName($name)
    {
        $this->name = $name . ' 👤';
    }
}
