<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Game</title>
</head>

<body>
	<!-- <form action="" method="POST">
		<label for="username">Enter your name</label>
		<input id="username" type="text" name="username">
	</form> -->

	<p>Word to translate:</p>
	<p class="word-to-translate"><?= $game->getWordToTranslate() ?></p>
	<!-- TODO: add a form for the user to play the game -->
	<form action="" method="POST">
		<label for="word">enter word</label>
		<input id="word" type="text" name="word">
	</form>

	<?php if (!empty($game->userFeedback)) : ?>
		<p><?= $game->userFeedback ?></p>
	<?php endif ?>
</body>

</html>