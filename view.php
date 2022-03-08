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
		<div class="username-form full-vp">
			<form action="" method="POST" class="username">
				<label for="username">Enter your name</label>
				<input id="username" type="text" name="username">
			</form>
		</div>
	<?php elseif ($game->gameState === 1) : ?>
		<div class="win full-vp">
			<p>you win</p>
			<div>
				<p>Score: <?= $game->player->score ?></p>
				<p>Errors: <?= $game->player->errors ?></p>
			</div>
			<form action="" method="POST">
				<button name="reset">play again</button>
			</form>
		</div>
	<?php elseif ($game->gameState === -1) : ?>
		<div class="loose full-vp">
			<p>you loose</p>
			<div>
				<p>Score: <?= $game->player->score ?></p>
				<p>Errors: <?= $game->player->errors ?></p>
			</div>
			<form action="" method="POST">
				<button name="reset">play again</button>
			</form>
		</div>
	<?php else : ?>
		<div class="top-bar">
			<p>Hello <?= $game->player->name ?></p>
			<p><?= $game->player->score ?> √ - <?= $game->player->errors ?> ×</p>
			<form action="" method="POST">
				<button name="reset">reset</button>
			</form>
		</div>

		<hr>

		<div class="from">
			<p class="language">English:</p>
			<p class="word-to-translate"><?= $game->getWordToTranslate() ?></p>
			<form action="" method="POST">
				<button name="pass">pass</button>
			</form>
		</div>

		<div class="to">
			<p class="language">French:</p>
			<form action="" method="POST" class="translation-input-form">
				<input id="word" type="text" name="word" placeholder="enter translation">
			</form>
		</div>

		<?php if (!empty($game->userFeedback)) : ?>
			<p class="user-feedback"><?= $game->userFeedback ?></p>
		<?php endif ?>

	<?php endif ?>

</body>

</html>