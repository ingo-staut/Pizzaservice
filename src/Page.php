<?php

/**
 * This abstract class is a common base class for all
 * HTML-pages to be created.
 * It manages access to the database and provides operations
 * for outputting header and footer of a page.
 * Specific pages have to inherit from that class.
 * Each inherited class can use these operations for accessing the db
 * and for creating the generic parts of a HTML-page.
 */
abstract class Page
{
    // --- ATTRIBUTES ---
    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;

    // --- OPERATIONS ---
    /**
     * Connects to DB and stores
     * the connection in member $_database.
     * Needs name of DB, user, password.
     *
     * @return none
     */
    protected function __construct()
    {
        require_once 'pwd.php';
        header("Content-type: text/html; charset=UTF-8");/* to do: create instance of class MySQLi */;

        $this->_database = new MySQLi($host, $user, $pwd, $dbname);
        $this->_database->set_charset("utf8");
    }

    /**
     * Closes the DB connection and cleans up
     *
     * @return none
     */
    protected function __destruct()
    {
        $this->_database->close();
    }

    /**
     * Generates the header section of the page.
     * i.e. starting from the content type up to the body-tag.
     * Takes care that all strings passed from outside
     * are converted to safe HTML by htmlspecialchars.
     *
     * @param $headline $headline is the text to be used as title of the page
     *
     * @return none
     */
    protected function generatePageHeader($headline)
    {
        header("Content-type: text/html; charset=UTF-8");

        echo <<<EOT
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8"/>
            <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>
            <script src="pizzaservice.js"></script>
EOT;
    }

    protected function generatePageHeaderSecond($headline)
    {
        $headline = htmlspecialchars($headline);
        echo <<<EOT
            <title>$headline</title>
            <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
        </head>
        <body>
        <div class="topnav" id="myTopnav">
            <a href="index.php"><img src="images/pizza-logo.png" alt="logo"></a>
            <a href="bestellung.php">Bestellung</a>
            <a href="baecker.php">Bäcker</a>
            <a href="fahrer.php">Fahrer</a>
            <a href="kunde.php">Kunde</a>
            <a href="javascript:void(0);" class="icon" onclick="switchNavBar()">&#9776;</a>
        </div>
        <br>
EOT;
    }

    /**
     * Outputs the end of the HTML-file i.e. /body etc.
     *
     * @return none
     */
    protected function generatePageFooter()
    {
        echo "</body>";
        echo "</html>";
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If every page is supposed to do something with submitted
     * data do it here. E.g. checking the settings of PHP that
     * influence passing the parameters (e.g. magic_quotes).
     *
     * @return none
     */
    protected function processReceivedData()
    {
        if (get_magic_quotes_gpc()) {
            throw new Exception
            ("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
        }
    }
} // end of class
