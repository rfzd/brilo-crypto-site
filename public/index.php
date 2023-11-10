<?php

declare(strict_types=1);

use BrioCryptoSite\Kernel;

require_once sprintf('%s/vendor/autoload_runtime.php', dirname(path: __DIR__));

return static function (array $context): Kernel {
    return new Kernel(
		environment: $context['APP_ENV'],
		debug: (bool) $context['APP_DEBUG'],
	);
};
