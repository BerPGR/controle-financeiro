<?php

namespace Mp\Controle;

use Flight;
use flight\Container;
use Mp\Controle\Repositories\EntriesRepository;
use Mp\Controle\Services\EntriesService;
use PDO;

class DInjection {
    public static function inject(array $config) {
        $container = new Container;
        
        $container->set(PDO::class, fn(): PDO => new PDO($config['db']['dsn'], null, null));
        $container->set(EntriesService::class, EntriesService::class);
        $container->set(EntriesRepository::class, EntriesRepository::class);

        Flight::registerContainerHandler([$container, 'get']);
    }
}


