<?php

/**
 * Inspiration https://github.com/cmall/LocalHomePage
 */

$configs = [
    ['name' => 'nginx', 'required' => true],
    ['name' => 'mariadb', 'required' => true],
    ['name' => 'mailhog', 'required' => true],
    ['name' => 'rabbitmq', 'required' => false],
];

foreach ($configs as $config) {
    $configFile = sprintf('config/%s.config.php', $config['name']);

    $fileExists = file_exists($configFile);
    if ($config['required'] && !$fileExists) {
        die(sprintf('Le fichier de config "%s" est manquant', $configFile));
    }

    $fileExists && require_once $configFile;
}

$phpVersion = phpversion();
$phpErrorLog = ini_get('error_log');

try {
    $pdo = new PDO(sprintf('mysql:host=%s:%d;charset=utf8', $mariadbHost, $mariadbPort),  $mariadbUser, $mariadbPassword);
    $mariadbVersion = $res = $pdo->query('select version()')->fetchColumn();
} catch (\Exception $exception) {
    $mariadbVersion = 'N/A';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>mac-playbook</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            background: url('background.png');
        }
        #main-content {
            padding-top: 10px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">🏠 mac-playbook</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="phpinfo.php" target="_blank">phpinfo()</a></li>
            <li class="nav-item"><a class="nav-link" href="http://<?php echo $mailhogUi; ?>" target="_blank">Mailhog</a></li>
            <?php if(isset($rabbitmqManagementUrl)): ?>
                <li class="nav-item"><a class="nav-link" href="<?php echo $rabbitmqManagementUrl ?>" target="_blank">RabbitMQ</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <div >
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="https://github.com/notFloran/mac-playbook" target="_blank">Github</a></li>
        </ul>
    </div>
</nav>

<div id="main-content" class="container-fluid">
    <div class="row">

        <div class="col-6">
            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2>PHP</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Version</th>
                        <td class="w-50"><?php echo $phpVersion; ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">phpinfo()</th>
                        <td class="w-50"><a href="phpinfo.php" target="_blank">Ouvrir</a></td>
                    </tr>
                    <tr>
                        <th class="w-50">Log</th>
                        <td class="w-50"><?php echo $phpErrorLog; ?></td>
                    </tr>
                    </tbody>
                </table>

                <h2>MariaDB</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Version</th>
                        <td class="w-50"><?php echo $mariadbVersion; ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Host</th>
                        <td class="w-50"><?php echo $mariadbHost; ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Port</th>
                        <td class="w-50"><?php echo $mariadbPort; ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Utilisateur</th>
                        <td class="w-50"><?php echo $mariadbUser; ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Mot de passe</th>
                        <td class="w-50"><?php echo $mariadbPassword; ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Log</th>
                        <td class="w-50"><?php echo $mariadbLogPath; ?></td>
                    </tr>
                    </tbody>
                </table>

                <h2>Mailhog</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Interface web</th>
                        <td class="w-50"><a href="http://<?php echo $mailhogUi; ?>" target="_blank">Ouvrir</a></td>
                    </tr>
                    <tr>
                        <th class="w-50">Serveur SMTP</th>
                        <td class="w-50"><?php echo $mailhogSmtp; ?></td>
                    </tr>
                    </tbody>
                </table>

                <?php if(isset($rabbitmqManagementUrl)): ?>
                <h2>RabbitMQ</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Interface web</th>
                        <td class="w-50"><a href="<?php echo $rabbitmqManagementUrl; ?>" target="_blank">Ouvrir</a></td>
                    </tr>
                    <tr>
                        <th class="w-50">Host</th>
                        <td class="w-50"><?php echo $rabbitmqHost; ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Port</th>
                        <td class="w-50"><?php echo $rabbitmqPort; ?></td>
                    </tr>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-6">
            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2>Nginx</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Log</th>
                        <td class="w-50"><?php echo $webServerLogPath; ?></td>
                    </tr>
                    </tbody>
                </table>

                <h3>Sites</h3>

                <table class="table table-striped table-bordered">
                    <thead>
                        <th class="w-50">Nom</th>
                        <th class="w-50">Chemin</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($sites as $site) {
                        echo <<<HTML
                        <tr>
                            <th><a target="_blank" href="{$site['url']}">{$site['name']}</a></th>
                            <td>{$site['path']}</td>
                        </tr>
HTML;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
