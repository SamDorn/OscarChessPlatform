<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OscarChessPlatform</title>

</head>

<body>
    <a href="index.php?action=vsComputer">Gioca contro il PC</a>
    <?php if (!isset($_SESSION["username"])) : ?>
        <a href="index.php?action=login">Effettua il login</a>
    <?php endif; ?>
</body>

</html>