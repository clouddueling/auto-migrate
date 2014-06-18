This class can be used to generate diffs between a MySQL XML dump
file, and a database which currently exists. It will tell you the missing
tables and columns (return as an array), or generate MySQL queries to add the
missing columns in.

Still a bit of a work-in-progress, please report bugs


# Getting Started

1. Export your database to XML schema files.
2. Use the class to loop through each schema file and update your database.

===================
Usage:
===================

Generate a MySQL Dump file:

mysqldump --xml --no-data testuser -utestuser -ptest1 > structure.xml

Then call the command line script (diffgen):

    diffgen -utestuser -ptest1 -dtestuser -hlocalhost -fstructure.xml -tshow

    -u  Database User
    -p  Database Password
    -d  Database Name
    -h  Database Host
    -f  Dump File Path
    -t  "show" or "run" - show will output the SQL, "run" will run the SQL

There's also a class file (which it is all from), which you can use to
integrate into your own custom scripts (as-is the case with phpVMS, which
is distributed with the structure.xml, and it "shapes" the database on the
remote server properly).


===================
Class Usage:
===================

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


## Todo
 - Support table engines changing from MyISAM to InnoDB
 - Create migrations for up and down


@author Nabeel Shahzad <https://github.com/nshahzad/MySQLDiff>
