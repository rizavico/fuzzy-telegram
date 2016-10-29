<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // MySQL Database
        "db" => [
            "host" => getenv('RDS_HOSTNAME'),
            "database" => getenv('RDS_DB_NAME'),
            "username" => getenv('RDS_USERNAME'),
            "password" => getenv('RDS_PASSWORD')
        ],
    ],
];
