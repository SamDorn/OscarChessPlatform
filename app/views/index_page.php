<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OscarChessPlatform</title>
</head>

<body>
    <a href="vsComputer">Gioca contro il PC</a>
    <?php if (isset($_SESSION["username"])) : ?>
        <a href="vsPlayer">Gioca online</a>
    <?php endif; ?>
    <?php if (!isset($_SESSION["username"])) : ?>
        <a href="login">Effettua il login</a>
    <?php endif; ?>
    <a href="puzzle">Resolve puzzles</a>
</body>

</html>