<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require sprintf('%s/vendor/autoload.php', dirname(path: __DIR__));

$bootstrapFile = sprintf('%s/config/bootstrap.php', dirname(path: __DIR__));
if (file_exists(filename: $bootstrapFile) === true) {
    require $bootstrapFile;
} elseif (method_exists(object_or_class: Dotenv::class, method: 'bootEnv') === true) {
    (new Dotenv())->bootEnv(path: sprintf('%s/.env', dirname(path: __DIR__)));
}
