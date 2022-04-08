<?php

return [
    'path' => 'app/Packages',

    'namespace' => 'App\\Packages',

    'commands' => [
        \CCast\NovaPackager\Commands\CreateActionCommand::class,
        \CCast\NovaPackager\Commands\CreateCardCommand::class,
        \CCast\NovaPackager\Commands\CreateCustomFilterCommand::class,
        \CCast\NovaPackager\Commands\CreateDashboardCommand::class,
        \CCast\NovaPackager\Commands\CreateFilterCommand::class,
        \CCast\NovaPackager\Commands\CreateFieldCommand::class,
        \CCast\NovaPackager\Commands\CreateLensCommand::class,
        \CCast\NovaPackager\Commands\CreateMigrationCommand::class,
        \CCast\NovaPackager\Commands\CreateModelCommand::class,
        \CCast\NovaPackager\Commands\CreatePackageCommand::class,
        \CCast\NovaPackager\Commands\CreatePartitionCommand::class,
        \CCast\NovaPackager\Commands\CreatePolicyCommand::class,
        \CCast\NovaPackager\Commands\CreateProgressCommand::class,
        \CCast\NovaPackager\Commands\CreateResourceCommand::class,
        \CCast\NovaPackager\Commands\CreateResourceToolCommand::class,
        \CCast\NovaPackager\Commands\CreateServiceProviderCommand::class,
        \CCast\NovaPackager\Commands\CreateToolCommand::class,
        \CCast\NovaPackager\Commands\CreateTrendCommand::class,
        \CCast\NovaPackager\Commands\CreateValueCommand::class,
        \CCast\NovaPackager\Commands\SeedCommand::class
    ]
];
