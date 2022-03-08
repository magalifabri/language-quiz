<?php

define('CORRECT', 1);
define('INCORRECT', 2);
define('GOOD_ENOUGH', 3);

class LanguageGame
{
    private array $words;
    public string $userFeedback;
    public Player $player;
    public int $gameState;

    public function __construct()
    {
        $this->gameState = 0;

        // fill $words
        foreach (Data::words() as $frenchTranslation => $englishTranslation) {
            $this->words[] = new Word($englishTranslation, $frenchTranslation);
        }

        // (re)create player
        if (empty($_SESSION['user'])) {
            $this->player = new Player('Anonymous', 0);
            $_SESSION['user'] = $this->player;
        } else {
            $this->player = $_SESSION['user'];
        }
    }

    public function run(): void
    {
        // handle reset button
        if (array_key_exists('reset', $_POST)) {
            $this->reset();
        }

        // only continue if game is running
        if ($this->gameState !== 0) {
            return;
        }

        // set username
        if (!empty($_POST['username'])) {
            $this->player->setName($_POST['username']);
        }

        // select random word
        if (empty($_SESSION['wordIndex'])) {
            $this->selectRandomWord();
        }

        // check user input
        if (!empty($_POST['word'])) {
            $givenAnswer = $_POST['word'];
            $selectedWord = $this->words[$_SESSION['wordIndex']];

            $correct = $selectedWord->verify($givenAnswer);

            if ($correct === CORRECT) {
                $this->userFeedback =
                    'Correct!'
                    . '<br>'
                    . '<b><i>' . $givenAnswer . '</i></b>' . ' (FR) is ' . '<b><i>' . $selectedWord->word . '</i></b>' . ' (EN).'
                    . '<br>'
                    . '<br>'
                    . 'New word selected.';

                $this->selectRandomWord();

                $this->player->score = $this->player->score + 1;
            } elseif ($correct === INCORRECT) {
                $this->userFeedback =
                    'Wrong!'
                    . '<br>'
                    . '<b><i>' . $givenAnswer . '</i></b>' . ' (FR) is not ' . '<b><i>' . $selectedWord->word . '</i></b>' . ' (EN).'
                    . '<br>'
                    . '<br>'
                    . 'Try again.';

                $this->player->errors = $this->player->errors + 1;
            } else {
                $this->userFeedback =
                    'Good enough!'
                    . '<br>'
                    . 'Your answer: <b><i>' . $givenAnswer . '</i></b>.'
                    . '<br>'
                    . '<b><i>' . $selectedWord->answer . '</i></b> (FR) is ' . '<b><i>' . $selectedWord->word . '</i></b>' . ' (EN).'
                    . '<br>'
                    . '<br>'
                    . 'New word selected.';

                $this->selectRandomWord();

                $this->player->score = $this->player->score + 1;
            }
        }

        // handle pass button
        if (array_key_exists('pass', $_POST)) {
            $this->selectRandomWord();
        }

        // check win / loose conditions
        if ($this->player->score >= 10) {
            $this->gameState = 1;
            return;
        } elseif ($this->player->errors >= 10) {
            $this->gameState = -1;
            return;
        }

        // save player state
        $_SESSION['user'] = $this->player;
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
