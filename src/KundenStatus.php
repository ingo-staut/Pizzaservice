<?php

require_once './Page.php';

class KundenStatus extends Page
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
            if($stmt = $this->_database->prepare("SELECT Angebot.PizzaName , Status FROM BestelltePizza Join Angebot on Angebot.PizzaNummer = BestelltePizza.fPizzaNummer where BestelltePizza.fBestellungID = $bestellid")) {
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
        $jsonAusgabe = $this->getViewData();
        if (!empty($jsonAusgabe)) {
            $serializedData = json_encode($jsonAusgabe, JSON_UNESCAPED_UNICODE);
            echo $serializedData;
        }
        else{
            print_r("Es wurden noch keine Pizzen in dieser Session bestellt");
        }
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();

        header("Content-Type: application/json; charset=UTF-8");
    }

    public static function main()
    {
        try {
            session_start();
            $page = new KundenStatus();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

KundenStatus::main();
