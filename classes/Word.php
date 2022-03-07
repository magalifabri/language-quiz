<?php

class Word
{
    // TODO: add word (FR) and answer (EN) - (via constructor or not? why?)
    public string $word;
    public string $answer;

    public function __construct($word, $answer)
    {
        $this->word = $word;
        $this->answer = $answer;
    }

    public function verify(string $answer): bool
    {
        // TODO: use this function to verify if the provided answer by the user matches the correct one
        // Bonus: allow answers with different casing (example: both bread or Bread can be correct answers, even though technically it's a different string)
        // Bonus (hard): can you allow answers with small typo's (max one character different)?

        if (strtolower($answer) === strtolower($this->answer)) {
            return true;
        } else {
            $lengthDif = strlen($answer) - strlen($this->answer);
            $errorMargin = strlen($this->answer) - similar_text(strtolower($answer), strtolower($this->answer));

            if (
                ($lengthDif === -1 && $errorMargin === 1)
                || ($lengthDif === 0 && $errorMargin === 1)
                || ($lengthDif === 1 && $errorMargin === 0)
            ) {
                return true;
            } else {
                return false;
            }
        }
    }
}
