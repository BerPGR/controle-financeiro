<?php

namespace Mp\Controle;

use Flight;
use flight\Container;
use Mp\Controle\Repositories\CardsRepository;
use Mp\Controle\Repositories\EntriesRepository;
use Mp\Controle\Repositories\IncomeRepository;
use Mp\Controle\Services\CardsService;
use Mp\Controle\Services\EntriesService;
use Mp\Controle\Services\IncomeService;
use PDO;

class DInjection
{
    public static function inject(array $config)
    {
        $container = new Container;

        $container->set(PDO::class, fn(): PDO => new PDO(
            "{$config['db']['driver']}:host={$config['db']['host']};port={$config['db']['port']};dbname={$config['db']['database']}",
            $config['db']['username'],
            $config['db']['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        ));
        $container->set(EntriesService::class, EntriesService::class);
        $container->set(EntriesRepository::class, EntriesRepository::class);
        $container->set(CardsService::class, CardsService::class);
        $container->set(CardsRepository::class, CardsRepository::class);
        $container->set(IncomeService::class, IncomeService::class);
        $container->set(IncomeRepository::class, IncomeRepository::class);

        Flight::registerContainerHandler([$container, 'get']);
    }
}
