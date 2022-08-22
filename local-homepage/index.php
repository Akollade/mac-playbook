<?php

$files = glob( __DIR__ . '/config/*.php');

foreach ($files as $file) {
    require($file);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>mac-playbook</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
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
            <?php if(extension_loaded('xdebug')): ?><li class="nav-item"><a class="nav-link" href="xdebug-info.php" target="_blank">xdebug_info()</a></li><?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo $sites['mailhog']['url']; ?>" target="_blank">Mailhog</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $sites['minio']['url']; ?>" target="_blank">Minio</a></li>
        </ul>
    </div>
    <div >
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="https://github.com/Akollade/mac-playbook" target="_blank">Github</a></li>
        </ul>
    </div>
</nav>

<div id="main-content" class="container">
    <div class="row">
        <div class="col">
            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2 class="text-center">PHP</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Verison</th>
                        <td class="w-50"><?php echo phpversion(); ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Log</th>
                        <td class="w-50"><?php echo ini_get('error_log'); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2 class="text-center">Nginx</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Log</th>
                        <td class="w-50"><?php echo $webServerLogPath; ?></td>
                    </tr>
                    </tbody>
                </table>

                <h3 class="text-center">Sites</h3>

                <table class="table table-striped table-bordered">
                    <thead>
                        <th class="w-50">Nom</th>
                        <th class="w-50">Chemin</th>
                    </thead>
                    <tbody>
                    <?php foreach ($sites as $site): ?>
                        <tr>
                            <th><a target="_blank" href="<?php echo $site['url'] ?>"><?php echo $site['name'] ?></a></th>
                            <td><?php echo $site['path'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if(isset($mariadbEnabled)): ?>
    <div class="row">
        <div class="col">
            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2 class="text-center">MariaDB</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">DSN</th>
                        <td class="w-50"><?php echo sprintf('mysql://%s:%s@%s:%s', $mariadbUser, $mariadbPassword, $mariadbHost, $mariadbPort); ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Log</th>
                        <td class="w-50"><?php echo $mariadbLogPath; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col">
            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2 class="text-center">Mailhog</h2>

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

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="bg-white rounded box-shadow my-3 p-3 border">
                <h2 class="text-center">Minio</h2>

                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th class="w-50">Port</th>
                        <td class="w-50"><?php echo $minioPort; ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Access Key</th>
                        <td class="w-50"><?php echo $minioAccessKey; ?></td>
                    </tr>
                    <tr>
                        <th class="w-50">Secret Key</th>
                        <td class="w-50"><?php echo $minioSecretKey; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>
