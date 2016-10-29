<?php
    return array(
        "paths" => array(
            "migrations" => "migrations"
        ),
        "environments" => array(
            "default_migration_table" => "phinxlog",
            "default_database" => "default",
            "default" => array(
                "adapter" => "mysql",
                "host" => getenv('RDS_HOSTNAME'),
                "name" => getenv('RDS_DB_NAME'),
                "user" => getenv('RDS_USERNAME'),
                "pass" => getenv('RDS_PASSWORD')
            )
        )
    );