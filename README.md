# WARNING

This project is brand new but will be quickly maturing if it proves to be a big timesaver.

This class can be used to generate diffs between a MySQL XML dump
file, and a database which currently exists. It will tell you the missing
tables and columns (return as an array), or generate MySQL queries to add the
missing columns in.

# Getting Started

1. Install via composer:

    ```json
    {
        "require": {
            "clouddueling/auto-migrate": "dev-master"
        }
    }
    ```

1. Export your database to XML schema files.

    ```bash
    sh src/Export/exportMySQL.sh <hostname> <username> <database> <output_dir>
    ```

    Your output should look like:

    ```
    Creating schema: series
    Creating schema: services
    Creating schema: sessions
    Creating schema: slides
    Creating schema: speakers
    Creating schema: states
    Creating schema: steps
    Creating schema: subscription
    ```

    The XML files outputted will be how you manage what your database looks like from now on.

1. When you make a change to your schema files you then use `CloudDueling\AutoMigrate\MySQL` and to loop through each schema file and to alter your database.

    Example of available methods:

    ```php
        $params = array(
            'dbuser' => 'root',
            'dbpass' => 'root',
            'dbname' => 'database',
            'dbhost' => 'localhost'
        );

        try {
            $diff = new MySQLDiff($params);
        } catch(Exception $e) {
            echo $e->getMessage(); exit;
        }

        // This returns an array of what's missing in the database
        try {
            $diff_lines = $diff->getDiffs();
            var_dump($diff_lines);
         catch(Exception $e) {
            echo $e->getMessage(); exit;
        }

        // This returns SQL queries which can be run to fix the database
        try {
            $diff_lines = $diff->getSQLDiffs();
            var_dump($diff_lines);
        } catch(Exception $e) {
            echo $e->getMessage(); exit;
        }

        // This generates the SQL and actually runs all of them
        try {
            $diff_lines = $diff->runSQLDiff();
            var_dump($diff_lines);
        } catch(Exception $e) {
            echo $e->getMessage(); exit;
        }
    ```

    Example looping through a directory of schemas and update your database with them.

    Laravel 4 Task
    ```php
    // Coming soon, feel free to PR this
    ```

    Laravel 3 Task
    ```php
    <?php

    class Schema_Task {

        public function update($arguments)
        {
            $params = array(
                'dbuser' => Config::get('database.connections.mysql.username'),
                'dbpass' => Config::get('database.connections.mysql.password'),
                'dbname' => Config::get('database.connections.mysql.database'),
                'dbhost' => Config::get('database.connections.mysql.host')
            );

            $files = scandir(Config::get('schemas.path'));
            $differences = 0;

            foreach ($files as $file) {
                if (in_array($file, ['.','..','.DS_Store'])) {
                    continue;
                }

                $params['dumpxml'] = Config::get('schemas.path') . '/' . $file;

                try {
                    $diff = new CloudDueling\AutoMigrate\MySQL($params);
                    $diff_lines = $diff->getSQLDiffs();
                    if (count($diff_lines) == 0) {
                        continue;
                    }

                    ++$differences;

                    echo "Difference found: {$params['dumpxml']}\n" .
                    " - " . implode("\n - ", $diff_lines) . "\n\n";
                    $diff->runSQLDiff();
                } catch(Exception $e) {
                    echo $e->getMessage() . "\n";
                    exit;
                }
            }

            echo "Differences found: {$differences}\n";
        }

    }
    ```

# Todo
 - UPGRADE TO PDO!!!! (original script is dependent on mysql_connect, maybe Eloquent?)
 - Create example artisan tasks for Laravel 3 and 4
 - Create a class that can generate a skeleton XML file for new tables
 - Support table engines changing from MyISAM to InnoDB
 - Create an adapter that can create migrations for up and down
 - Add to Travis
 - Implement an interface encouraging hexagonal structure
 - Remove 'db' from the parameters for connecting
 - Extract the deeply nested loops to smaller understandable classes.

# Goals
 - Allow for future database types to be able to use this with their own class and export script.
 - PSR compliant

# Contributing

Please create an issue first with your idea or bug for discussion so no one codes unnecessarily.

# Credits

Much thanks to Nabeel Shahzad who originally wrote this class.

# License

The MIT License (MIT). Please see [License File](https://github.com/clouddueling/auto-migrate/blob/master/LICENSE) for more information.

