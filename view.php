<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Game</title>
</head>

<body>
	<form action="" method="POST">
		<label for="username">Enter your name</label>
		<input id="username" type="text" name="username">
	</form>
	<p>Hello <?= $_SESSION['username'] ?? '' ?></p>
	<p>Score: <?= $_SESSION['score'] ?></p>
	<hr>

	<p>Word to translate:</p>
	<p class="word-to-translate" style="font-weight: bold"><?= $game->getWordToTranslate() ?></p>
	<form action="" method="POST">
		<button name="pass">pass</button>
	</form>
	<!-- TODO: add a form for the user to play the game -->
	<form action="" method="POST">
		<label for="word">enter word</label>
		<input id="word" type="text" name="word">
	</form>

	<?php if (!empty($game->userFeedback)) : ?>
		<p><?= $game->userFeedback ?></p>
	<?php endif ?>

	<form action="" method="POST">
		<button name="reset">reset</button>
	</form>
</body>

</html>