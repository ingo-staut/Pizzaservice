<?php

require_once './Page.php';

class Kunde extends Page
{

    protected function __construct()
    {
        parent::__construct();
    }

    protected function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData()
    {
        if (isset($_SESSION["BestellID"])) {
            $bestellid = $_SESSION["BestellID"];

            if ($stmt = $this->_database->prepare("SELECT Angebot.PizzaName , Status FROM BestelltePizza Join Angebot on Angebot.PizzaNummer = BestelltePizza.fPizzaNummer where BestelltePizza.fBestellungID = $bestellid")) {
                $stmt->execute();
                $stmt->bind_result($PizzaName, $Status);
                while ($stmt->fetch()) {
                    $bestellte_pizzen[] = array(
                        'PizzaName' => $PizzaName,
                        'Status' => $Status
                    );
                }
                $stmt->close();
            }
            return $bestellte_pizzen;
        }
    }

    protected function generateView()
    {
        $headline = htmlspecialchars('Kunde');
        header("Content-type: text/html; charset=UTF-8");

        $this->generatePageHeader($headline);

        echo "<script src='pizzaservice.js'></script>";

        echo <<< EOS
            <title>$headline</title>
            <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
        </head>
        <body onload="onloadPage()">
        <div class="topnav" id="myTopnav">
            <a href="#"><img src="images/pizza-logo.png" alt="logo"></a>
            <a href="bestellung.php">Bestellung</a>
            <a href="baecker.php">Bäcker</a>
            <a href="fahrer.php">Fahrer</a>
            <a href="kunde.php">Kunde</a>
        <a href="javascript:void(0);" class="icon" onclick="switchNavBar()">&#9776;</a>
        </div>
        <br>
        <section class='layout_padding'>
EOS;

        $bestellte_pizzen = $this->getViewData();
        if (!empty($bestellte_pizzen)) {
            echo <<< EOT
            <script src="StatusUpdate.js"></script>
            <div id="Bestellungen"></div>
            <div>
                <form action="bestellung.php" method="post">
                    <button type="submit" accesskey="b" tabindex="1">Neue Bestellung</button>
                </form>
            </div>
EOT;
        } else {
            echo "<p>Es wurden noch keine Bestellungen unter dieser SessionID erstellt.</p>";
        }
        echo "</section>";
        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();
    }

    public static function main()
    {
        try {
            session_start();
            $page = new Kunde();
            $page->processReceivedData();
            $page->generateView();

        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Kunde::main();
