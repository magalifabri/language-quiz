<?php

class Player
{
    public string $name;
    public int $score;
    public int $errors;

    public function __construct($name)
    {
        $this->setName($name);
        $this->score = 0;
        $this->errors = 0;
    }

    public function setName($name)
    {
        $this->name = $name . ' 👤';
    }
}
