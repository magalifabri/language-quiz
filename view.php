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
				<input id="username" type="text" name="username" placeholder="username" autocomplete="off">
			</form>
		</div>
	<?php elseif ($game->gameState !== $game::RUNNING) : ?>
		<div class="full-vp">
			<?php if ($game->gameState === $game::WIN) : ?>
				<p class="end-condition">you win</p>
			<?php else : ?>
				<p class="end-condition">you lose</p>
			<?php endif ?>
			<div>
				<p><?= $game->player->score ?> √</p>
				<p><?= $game->player->errors ?> ×</p>
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

		<div class="grid">
			<p class="language from">English:</p>
			<p class="word-to-translate"><?= $game->getWordToTranslate() ?></p>
			<form action="" method="POST" class="pass">
				<button name="pass">pass (<?= $game->player->passes ?>)</button>
			</form>
			<p class="language to">French:</p>
			<form action="" method="POST" class="translation-input-form">
				<input id="word" type="text" name="word" placeholder="translation" autocomplete="off">
			</form>
		</div>

		<?php if (!empty($game->verificationStatusMsg)) : ?>
			<div class="verificationStatusMsg">
				<?= $game->verificationStatusMsg ?>
			</div>
		<?php endif ?>

	<?php endif ?>

</body>

</html>