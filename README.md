This class can be used to generate diffs between a MySQL XML dump
file, and a database which currently exists. It will tell you the missing
tables and columns (return as an array), or generate MySQL queries to add the
missing columns in.

Still a bit of a work-in-progress, please report bugs


# Getting Started

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

2. When you make a change to your schema files you then use `CloudDueling\AutoMigrate\MySQL` and to loop through each schema file and to alter your database.

# Class Usage:

```php
    $params = array(
        'dbuser' => 'testuser',
        'dbpass' => 'test1',
        'dbname' => 'testuser',
        'dbhost' => 'localhost',
        'dumpxml' => 'structure.xml',
    );

    try {
        $diff = new MySQLDiff($params);
    } catch(Exception $e) {
        echo $e->getMessage(); exit;
    }

    # This returns an array of what's missing in the database
    try {
        $diff_lines = $diff->getDiffs();
        var_dump($diff_lines);
     catch(Exception $e) {
        echo $e->getMessage(); exit;
    }

    # This returns SQL queries which can be run to fix the database
    try {
        $diff_lines = $diff->getSQLDiffs();
        var_dump($diff_lines);
    } catch(Exception $e) {
        echo $e->getMessage(); exit;
    }

    # This generates the SQL and actually runs all of them
    try {
        $diff_lines = $diff->runSQLDiff();
        var_dump($diff_lines);
    } catch(Exception $e) {
        echo $e->getMessage(); exit;
    }
```

# Todo
 - Support table engines changing from MyISAM to InnoDB
 - Create migrations for up and down
 - Add to Travis
