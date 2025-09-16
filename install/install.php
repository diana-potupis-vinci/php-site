<?php

$configFile = __DIR__ . '/../config/variables.php';

if(isset($_POST['button_install']) ){
    $db_host = trim(htmlspecialchars($_POST['db_host']));
    $db_port = trim(htmlspecialchars($_POST['db_port']));
    $db_user = trim(htmlspecialchars($_POST['db_user']));
    $db_name = trim(htmlspecialchars($_POST['db_name']));
    $db_pass = trim(htmlspecialchars($_POST['db_pass']));
    $admin_pass = password_hash(trim($_POST['admin_pass']), PASSWORD_DEFAULT);
    $db_lang = trim(htmlspecialchars($_POST['lang']));
    $db_currency = trim(htmlspecialchars($_POST['currency']));
    $db_timezone = trim(htmlspecialchars($_POST['timezone']));

    if(verifying_data($db_host, $db_port, $db_user, $db_pass, $admin_pass, $db_lang, $db_currency, $db_name, $db_timezone)){
        $configContent = "<?php\n";
        $configContent .= "\$db_host = '" . $db_host . "';\n";
        $configContent .= "\$db_port = '" . $db_port . "';\n";
        $configContent .= "\$db_user = '" . $db_user . "';\n";
        $configContent .= "\$db_name = '" . $db_name . "';\n";
        $configContent .= "\$db_pass = '" . $db_pass . "';\n";
        $configContent .= "\$admin_pass = '$admin_pass';\n";
        $configContent .= "\$db_lang = '" . $db_lang . "';\n";
        $configContent .= "\$db_currency = '" . $db_currency . "';\n";
        $configContent .= "\$db_timezone = '$db_timezone';\n";

        file_put_contents($configFile, $configContent);

        try {
            $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name";
            $pdo = new PDO($dsn, $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql1 = file_get_contents(__DIR__ . '/../sql/exercice_categories.sql');
            $pdo->exec($sql1);
        
            $sql2 = file_get_contents(__DIR__ . '/../sql/exercice_products.sql');
            $pdo->exec($sql2);
        
        } catch (PDOException $e) {
            echo "<h3>Erreur lors de l'installation :</h3><p>" . $e->getMessage() . "</p>";
        }
        
    }
}

if (file_exists($configFile) && filesize($configFile) > 0) {
    echo("<h3>Installation déjà effectuée.</h3>
         <p>Si vous voulez réinstaller, supprimez le fichier config/variables.php.</p>");
}else{
    show_html();
}

function verifying_data($db_host, $db_port, $db_user, $db_pass, $admin_pass, $db_lang, $db_currency, $db_name, $db_timezone){
    if($db_host == null || $db_port == null || $db_user == null || $db_pass == null || $admin_pass == null || $db_lang == null || $db_currency == null || $db_name == null || $db_timezone == null){
        return false;
    }
    return true;
}

function show_html(){
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Installation</title>
    </head>
    <body>
        <h2>Installation du site</h2>
        <form method="post" action="">
            <label>Serveur BD :</label><br>
            <input type="text" name="db_host" required><br><br>
    
            <label>Port :</label><br>
            <input type="text" name="db_port" ><br><br>
         
            <label>Utilisateur :</label><br>
            <input type="text" name="db_user" required><br><br>

            <label>Nom bd :</label><br>
            <input type="text" name="db_name" required><br><br>
    
            <label>Mot de passe BD :</label><br>
            <input type="password" name="db_pass"><br><br>

            <label>Mot de passe administrateur :</label><br>
            <input type="password" name="admin_pass" required><br><br>
    
            <label>Langue par défaut :</label><br>
            <select name="lang">
                <option value="fr">Français</option>
                <option value="en">English</option>
                <option value="ua">Українська</option>
            </select><br><br>
    
            <label>Unité monétaire :</label><br>
            <select name="currency">
                <option value="EUR">Euro (€)</option>
                <option value="USD">Dollar ($)</option>
                <option value="CAD">Dollar Canadien ($)</option>
            </select><br><br>

            <label>Fuseau horaire :</label><br>
            <select name="timezone">
                <option value="UTC">UTC</option>
                <option value="America/Toronto">America/Toronto</option>
                <option value="Europe/Paris">Europe/Paris</option>
                <option value="Asia/Tokyo">Asia/Tokyo</option>
            </select><br><br>

            <button type="submit" name="button_install">Install</button>
        </form>
    </body>
    </html>
    <?php
}