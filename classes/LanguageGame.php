<?php

class LanguageGame
{
    private array $words;
    private Word $wordToTranslate;

    public function __construct()
    {
        $this->words = [];

        // :: is used for static functions
        // They can be called without an instance of that class being created
        // and are used mostly for more *static* types of data (a fixed set of translations in this case)
        foreach (Data::words() as $frenchTranslation => $englishTranslation) {
            // TODO: create instances of the Word class to be added to the words array
            array_push($this->words, new Word($englishTranslation, $frenchTranslation));
        }

        $this->wordToTranslate = $this->words[rand(0, count($this->words) - 1)];
    }

    public function run(): void
    {
        // TODO: check for option A or B

        // Option A: user visits site first time (or wants a new word)
        // TODO: select a random word for the user to translate
        $wordToTranslate = $this->words[rand(0, count($this->words) - 1)];

        // Option B: user has just submitted an answer
        // TODO: verify the answer (use the verify function in the word class) - you'll need to get the used word from the array first
        if (!empty($_POST['word'])) {
            $givenAnswer = $_POST['word'];
        }

        // TODO: generate a message for the user that can be shown

    }

    public function getWordToTranslate()
    {
        return $this->wordToTranslate->word;
    }
}
