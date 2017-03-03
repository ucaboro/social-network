<?php
    /*$dbhost = "Localhost";
    $dbuser = "michael";
    $dbpass = "aQRpuC5GqyTFCHwD";
    $dbname = "networking";
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if(mysqli_connect_errno()){
        die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")" );
    }*/


class db {

    /*
     * Property containing the MySQLi connection object.
     * This is 'static' to save resources - the same connection will be used across multiple instances.
     */
    private static $mysqli;

    /*
     * Opens a new connection to the database.
     * Returns: nothing.
     */
    public function connect() {
        // Only set up the connection the first time this is called.
        if (!isset(self::$mysqli)) {
            // Details to use for connection
            $host = "Localhost";
            $username = "michael";
            $password = "aQRpuC5GqyTFCHwD";
            $dbname = "networking";


            // Create connection object and assign it to property
            self::$mysqli = new mysqli($host, $username, $password, $dbname);
        }

        // Check for errors
        if (!self::$mysqli) {
            // NOTE: This will kill the entire page!
            die("Database connection has failed: " . self::$mysqli->connect_error . " (" . self::$mysqli->connect_errno . ")");
        }
    }

    /*
     * Performs a query on the database.
     * $query: the query as a string.
     * Returns false on failure. For successful queries, returns a mysqli_result object or true depending on if the query requires data to be returned.
     */
    public function query($query) {
        return self::$mysqli->query($query);
    }

    /*
     * Prepares a SQL statement.
     * $query: the query as a string.
     * Returns false on failure. For successful queries, returns a mysqli_stmt object.
     */
    public function prepare($query) {
        return self::$mysqli->prepare($query);
    }

    /*
     * Returns the last mysqli error message.
     */
    public function getError() {
        return self::$mysqli->error;
    }

    public function closeConnection() {
        self::$mysqli->close();
    }

}

?>

