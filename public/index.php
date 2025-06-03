<?php

use Symfony\Component\Dotenv\Dotenv;
require dirname(__DIR__).'/vendor/autoload.php';

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

if (file_exists(dirname(__DIR__).'/.env')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

// Pour Platform.sh
if (isset($_ENV['PLATFORM_RELATIONSHIPS'])) {
    $relationships = json_decode(base64_decode($_ENV['PLATFORM_RELATIONSHIPS']), true);
    if (isset($relationships['database'][0])) {
        $creds = $relationships['database'][0];
        $_ENV['DATABASE_URL'] =
        $_SERVER['DATABASE_URL'] =
            sprintf(
                '%s://%s:%s@%s:%d/%s',
                $creds['scheme'],
                $creds['username'],
                $creds['password'],
                $creds['host'],
                $creds['port'],
                $creds['path']
            );
    }
}

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
