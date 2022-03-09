<?php

class Word
{
    const CORRECT = 1;
    const INCORRECT = 2;
    const ALMOST = 3;

    public string $word;
    public string $answer;

    public function __construct($word, $answer)
    {
        $this->word = $word;
        $this->answer = $answer;
    }

    public function verify(string $answer): int
    {
        if (strtolower($answer) === strtolower($this->answer)) {
            return self::CORRECT;
        } else {
            $lengthDif = strlen($answer) - strlen($this->answer);
            $errorMargin = strlen($this->answer) - similar_text(strtolower($answer), strtolower($this->answer));

            if (
                ($lengthDif === -1 && $errorMargin === 1)
                || ($lengthDif === 0 && $errorMargin === 1)
                || ($lengthDif === 1 && $errorMargin === 0)
            ) {
                return self::ALMOST;
            } else {
                return self::INCORRECT;
            }
        }
    }
}
