<?php

class LanguageGame
{
    private array $words;
    public string $userFeedback;
    public Player $player;
    public int $gameState;

    public function __construct()
    {
        $this->gameState = 0;

        // :: is used for static functions
        // They can be called without an instance of that class being created
        // and are used mostly for more *static* types of data (a fixed set of translations in this case)
        foreach (Data::words() as $frenchTranslation => $englishTranslation) {
            // TODO: create instances of the Word class to be added to the words array
            $this->words[] = new Word($englishTranslation, $frenchTranslation);
        }

        if (empty($_SESSION['user'])) {
            $this->player = new Player('Anonymous', 0);
            $_SESSION['user'] = serialize($this->player);
        } else {
            $this->player = unserialize($_SESSION['user']);
        }
    }

    public function run(): void
    {
        // reset button
        if (array_key_exists('reset', $_POST)) {
            $this->reset();
        }

        // check if game is running
        if ($this->gameState !== 0) {
            return;
        }

        // TODO: check for option A or B

        // Option A: user visits site first time (or wants a new word)
        // TODO: select a random word for the user to translate
        if (empty($_SESSION['wordIndex'])) {
            $this->selectRandomWord();
        }

        // Option B: user has just submitted an answer
        // TODO: verify the answer (use the verify function in the word class) - you'll need to get the used word from the array first
        if (!empty($_POST['word'])) {
            $givenAnswer = $_POST['word'];
            $selectedWord = $this->words[$_SESSION['wordIndex']];

            $correct = $selectedWord->verify($givenAnswer);

            if ($correct) {
                $this->userFeedback =
                    'Correct!'
                    . '<br>'
                    . '<b><i>' . $givenAnswer . '</i></b>' . ' (FR) is ' . '<b><i>' . $selectedWord->word . '</i></b>' . ' (EN).'
                    . '<br>'
                    . 'New word selected.';

                $this->selectRandomWord();

                $this->player->score = $this->player->score + 1;
            } else {
                $this->userFeedback =
                    'Wrong!'
                    . '<br>'
                    . '<b><i>' . $givenAnswer . '</i></b>' . ' (FR) is not ' . '<b><i>' . $selectedWord->word . '</i></b>' . ' (EN).'
                    . '<br>'
                    . 'Try again.';

                $this->player->errors = $this->player->errors + 1;
            }
        }

        // TODO: generate a message for the user that can be shown

        // set username
        if (!empty($_POST['username'])) {
            $this->player->setName($_POST['username']);
        }

        // pass button: give new word
        if (array_key_exists('pass', $_POST)) {
            $this->selectRandomWord();
        }

        // win / loose trigger
        if ($this->player->score >= 10) {
            $this->gameState = 1;
            return;
        } elseif ($this->player->errors >= 10) {
            $this->gameState = -1;
            return;
        }

        // save user changes
        $_SESSION['user'] = serialize($this->player);
    }

    public function selectRandomWord()
    {
        $_SESSION['wordIndex'] = rand(0, count($this->words) - 1);
    }

    public function getWordToTranslate()
    {
        return $this->words[$_SESSION['wordIndex']]->word;
    }

    private function reset()
    {
        $this->selectRandomWord();
        $this->player->score = 0;
        $this->player->errors = 0;
        $this->player->setName('Anonymous');
        session_unset();
        session_destroy();
    }
}
