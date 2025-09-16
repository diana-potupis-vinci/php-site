<?php
session_start();
$configFile = __DIR__ . '/../config/variables.php';
include $configFile;

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: config.php");
    exit;
}

if (!isset($_SESSION['is_admin'])) {
    if (isset($_POST['login'])) {
        if (password_verify($_POST['admin_pass'], $admin_pass)) {
            $_SESSION['is_admin'] = true;
            header("Location: config.php");
            exit;
        } else {
            $error = "Mot de passe incorrect.";
        }
    }

    echo '<h2>Connexion administrateur</h2>';
    if (isset($error)) echo "<p>$error</p>";
    echo '<form method="post">
            <label>Mot de passe :</label><br>
            <input type="password" name="admin_pass" required><br><br>
            <button type="submit" name="login">Se connecter</button>
          </form>';
    exit;
}

if (isset($_POST['save'])) {
    $db_lang = $_POST['lang'];
    $db_currency = $_POST['currency'];
    $db_timezone = $_POST['timezone'];

    $configContent = "<?php\n";
    $configContent .= "\$db_host = '$db_host';\n";
    $configContent .= "\$db_port = '$db_port';\n";
    $configContent .= "\$db_user = '$db_user';\n";
    $configContent .= "\$db_name = '" . $db_name . "';\n";
    $configContent .= "\$db_pass = '$db_pass';\n";
    $configContent .= "\$admin_pass = '$admin_pass';\n";
    $configContent .= "\$db_lang = '$db_lang';\n";
    $configContent .= "\$db_currency = '$db_currency';\n";
    $configContent .= "\$db_timezone = '$db_timezone';\n";

    file_put_contents($configFile, $configContent);
    echo "<p>Configuration mise à jour.</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Configuration</title></head>
<body>
    <h2>Configuration du site</h2>
    <form method="post">
        <label>Langue :</label><br>
        <select name="lang">
            <option value="fr" <?= $db_lang == 'fr' ? 'selected' : '' ?>>Français</option>
            <option value="en" <?= $db_lang == 'en' ? 'selected' : '' ?>>English</option>
            <option value="ua" <?= $db_lang == 'ua' ? 'selected' : '' ?>>Українська</option>
        </select><br><br>
        <label>Monnaie :</label><br>
        <select name="currency">
            <option value="EUR" <?= $db_currency == 'EUR' ? 'selected' : '' ?>>Euro (€)</option>
            <option value="USD" <?= $db_currency == 'USD' ? 'selected' : '' ?>>Dollar ($)</option>
            <option value="CAD" <?= $db_currency == 'CAD' ? 'selected' : '' ?>>Dollar Canadien ($)</option>
        </select><br><br>
        <label>Fuseau horaire :</label><br>
        <select name="timezone">
            <option value="UTC" <?= $db_timezone == 'UTC' ? 'selected' : '' ?>>UTC</option>
            <option value="America/Toronto" <?= $db_timezone == 'America/Toronto' ? 'selected' : '' ?>>America/Toronto</option>
            <option value="Europe/Paris" <?= $db_timezone == 'Europe/Paris' ? 'selected' : '' ?>>Europe/Paris</option>
            <option value="Asia/Tokyo" <?= $db_timezone == 'Asia/Tokyo' ? 'selected' : '' ?>>Asia/Tokyo</option>
        </select><br><br>
        <button type="submit" name="save">Sauvegarder</button>
    </form>
    <form method="post" style="margin-top: 20px;">
        <button type="submit" name="logout">Se déconnecter</button>
    </form>
</body>
</html>
