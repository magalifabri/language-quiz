<?php

class LanguageGame
{
    private array $words;
    public string $verificationStatusMsg;
    public Player $player;
    public array $wordsTimesSelected;

    public int $gameState;
    const RUNNING = 1;
    const WIN = 2;
    const LOOSE = 3;

    public function __construct()
    {
        $this->gameState = self::RUNNING;
        $this->initWords();
        $this->initPlayer();

        if (!empty($_SESSION['wordsTimesSelected'])) {
            $this->wordsTimesSelected = $_SESSION['wordsTimesSelected'];
        } else {
            for ($i = 0; $i < count($this->words); $i++) {
                $this->wordsTimesSelected[$i] = 0;
            }
        }
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
        // check if username has been given
        if ($this->player->name === 'Anonymous 👤') {
            if (!empty($_POST['username'])) {
                $this->player->setName($_POST['username']);
            } else {
                return;
            }
        }

        // handle reset button
        if (array_key_exists('reset', $_POST)) {
            $this->reset();
            return;
        }

        // check game state
        if (($this->gameState = $this->setGameState()) !== self::RUNNING) {
            return;
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
                $this->handleAlmostTranslation($givenAnswer, $selectedWord);
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
                <p>Wrong!</p>
                <p><b><i> $selectedWord->answer </i></b> (FR) is <b><i> $selectedWord->word </i></b> (EN)</p>
                <p><small>( Your answer: <i> $givenAnswer </i>)</small></p>
                <p>Correct your answer</p>
            ";

        if ($_SESSION['newWord'] === 0) {
            $this->player->errors = $this->player->errors + 1;
        } else {
            $this->verificationStatusMsg .= '<p><small>( First error is free )</small></p>';
            $_SESSION['newWord'] = 0;
        }
    }

    private function handleAlmostTranslation($givenAnswer, $selectedWord)
    {
        $this->verificationStatusMsg =
            "
                <p>Almost!</p>
                <p><b><i> $selectedWord->answer </i></b> (FR) is <b><i> $selectedWord->word </i></b> (EN)</p>
                <p><small>( Your answer: <i> $givenAnswer </i>)</small></p>
                <p>Correct your answer</p>
            ";
    }

    public function selectRandomWord()
    {
        $currentWordIndex = $_SESSION['wordIndex'] ?? -1;

        while (1) {
            $newWordIndex = array_rand($this->words);
            if ($newWordIndex != $currentWordIndex) {
                break;
            }
        }

        if ($this->wordsTimesSelected[$newWordIndex] === 0) {
            $_SESSION['newWord'] = 1;
        } else {
            $_SESSION['newWord'] = 0;
        }

        $_SESSION['wordIndex'] = $newWordIndex;
        $this->wordsTimesSelected[$newWordIndex]++;
        $_SESSION['wordsTimesSelected'] = $this->wordsTimesSelected;
    }

    public function getWordToTranslate()
    {
        return $this->words[$_SESSION['wordIndex']]->word;
    }

    private function reset()
    {
        $this->player->score = 0;
        $this->player->errors = 0;
        $this->player->setName('Anonymous');
        session_unset();
        session_destroy();
    }
}
