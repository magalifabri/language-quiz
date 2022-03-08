<?php

class LanguageGame
{
    private array $words;
    public string $verificationStatusMsg;
    public Player $player;

    public int $gameState;
    const RUNNING = 1;
    const WIN = 2;
    const LOOSE = 3;

    public function __construct()
    {
        $this->gameState = self::RUNNING;
        $this->initWords();
        $this->initPlayer();
    }

    private function initWords()
    {
        foreach (Data::words() as $frenchTranslation => $englishTranslation) {
            $this->words[] = new Word($englishTranslation, $frenchTranslation);
        }
    }

    private function initPlayer()
    {
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

        // check game state
        if (($this->gameState = $this->setGameState()) !== self::RUNNING) {
            return;
        }

        // set username
        if (!empty($_POST['username'])) {
            $this->player->setName($_POST['username']);
        }

        // select random word
        if (!isset($_SESSION['wordIndex'])) {
            $this->selectRandomWord();
        }

        // check user input
        if (!empty($_POST['word'])) {
            $givenAnswer = $_POST['word'];
            $selectedWord = $this->words[$_SESSION['wordIndex']];

            $verificationStatus = $selectedWord->verify($givenAnswer);

            if ($verificationStatus === Word::CORRECT) {
                $this->handleCorrectTranslation($givenAnswer, $selectedWord);
            } elseif ($verificationStatus === Word::INCORRECT) {
                $this->handleIncorrectTranslation($givenAnswer, $selectedWord);
            } else {
                $this->handleGoodEnoughTranslation($givenAnswer, $selectedWord);
            }
        }

        // handle pass button
        if (array_key_exists('pass', $_POST)) {
            $this->selectRandomWord();
        }

        // check game state
        if (($this->gameState = $this->setGameState()) !== self::RUNNING) {
            return;
        }

        // save player state
        $_SESSION['user'] = $this->player;
    }

    private function setGameState(): int
    {
        if ($this->player->score === 10) {
            return self::WIN;
        } elseif ($this->player->errors === 10) {
            return self::LOOSE;
        } else {
            return self::RUNNING;
        }
    }

    private function handleCorrectTranslation($givenAnswer, $selectedWord)
    {
        $this->verificationStatusMsg =
            "
                <p>Correct!</p>
                <p><b><i> $givenAnswer </i></b> (FR) is <b><i> $selectedWord->word </i></b> (EN)</p>
                <p>New word selected</p>
            ";

        $this->selectRandomWord();

        $this->player->score = $this->player->score + 1;
    }

    private function handleIncorrectTranslation($givenAnswer, $selectedWord)
    {
        $this->verificationStatusMsg =
            "
                <p>Ehhh!</p>
                <p><b><i> $givenAnswer </i></b> (FR) is NOT <b><i> $selectedWord->word </i></b> (EN)</p>
                <p>Try again!</p>
            ";

        $this->player->errors = $this->player->errors + 1;
    }

    private function handleGoodEnoughTranslation($givenAnswer, $selectedWord)
    {
        $this->verificationStatusMsg =
            "
                <p>Good enough</p>
                <p>Your answer: <b><i> $givenAnswer </i></b>
                <p>Correct answer: <b><i> $selectedWord->answer </i></b> (FR) is <b><i> $selectedWord->word </i></b> (EN)</p>
                <p>New word selected</p>
            ";

        $this->selectRandomWord();

        $this->player->score = $this->player->score + 1;
    }

    public function selectRandomWord()
    {
        $_SESSION['wordIndex'] = array_rand($this->words);
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
