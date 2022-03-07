<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<link rel="stylesheet" href="./style.css">

	<title>Game</title>
</head>

<body>

	<?php if ($game->player->name === 'Anonymous 👤') : ?>
		<form action="" method="POST">
			<label for="username">Enter your name</label>
			<input id="username" type="text" name="username">
		</form>
	<?php elseif ($game->gameState === 1) : ?>
		<div class="win">
			<p>you win</p>
			<p>Score: <?= $game->player->score ?></p>
			<p>Errors: <?= $game->player->errors ?></p>
			<form action="" method="POST">
				<button name="reset">play again</button>
			</form>
		</div>
	<?php elseif ($game->gameState === -1) : ?>
		<div class="loose">
			<p>you loose</p>
			<p>Score: <?= $game->player->score ?></p>
			<p>Errors: <?= $game->player->errors ?></p>
			<form action="" method="POST">
				<button name="reset">play again</button>
			</form>
		</div>
	<?php else : ?>

		<p>Hello <?= $game->player->name ?></p>
		<p>Score: <?= $game->player->score ?></p>
		<p>Errors: <?= $game->player->errors ?></p>
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

	<?php endif ?>

</body>

</html>