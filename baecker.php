<?php

require_once './Page.php';

class Baecker extends Page
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
        if ($stmt = $this->_database->prepare("SELECT Angebot.PizzaName, BestelltePizza.PizzaID, BestelltePizza.Status FROM BestelltePizza Join Angebot on Angebot.PizzaNummer = BestelltePizza.fPizzaNummer WHERE Status ='bestellt' or Status ='im ofen'")) {
            $stmt->execute();
            $stmt->bind_result($PizzaName, $PizzaID, $PizzaStatus);
            while ($stmt->fetch()) {
                $bestellte_pizzen[] = array(
                    'PizzaName' => $PizzaName,
                    'PizzaId' => $PizzaID,
                    'PizzaStatus' => $PizzaStatus
                );
            }
            $stmt->close();
        }

        if (!empty($bestellte_pizzen)) {
            return $bestellte_pizzen;
        } else {
            print_r("Es wurden keine Pizzen bestellt!");
        }
    }

    protected function generateView()
    {
        $headline = htmlspecialchars('Baecker');
        header("Content-type: text/html; charset=UTF-8");
        $this->generatePageHeader($headline);

        echo "<meta http-equiv='refresh' content='5'>";
        echo "<script src='pizzaservice.js'></script>";

        $this->generatePageHeaderSecond($headline);

        $bestellte_pizzen = $this->getViewData();
        if (!empty($bestellte_pizzen)) {

            echo "<section class='layout_padding'>";
            echo "<form method="post" id="baeckerform">"
            foreach ($bestellte_pizzen as $pizzen) {
                if ($pizzen['PizzaStatus'] == "bestellt") {
                    echo <<<EOZ 
                    <p>{$pizzen['PizzaName']}</p>
                    <label><input class="radio" type="radio" name="pizza_{$pizzen['PizzaId']}" value="bestellt" checked="checked" onclick="document.forms['baeckerform'].submit()"> bestellt</label>
                    <label><input class="radio" type="radio" name="pizza_{$pizzen['PizzaId']}" value="im Ofen"  onclick="document.forms['baeckerform'].submit()"> im Ofen</label>
                    <label><input class="radio" type="radio" name="pizza_{$pizzen['PizzaId']}" value="fertig"   onclick="document.forms['baeckerform'].submit()">fertig</label>
EOZ;
                }
                if ($pizzen['PizzaStatus'] == "im Ofen") {
                    echo <<<EOA
                    <p>{$pizzen['PizzaName']}</p>
                    <label><input class="radio" type="radio" name="pizza_{$pizzen['PizzaId']}" value="bestellt" onclick="document.forms['baeckerform'].submit()">bestellt</label>
                    <label><input class="radio" type="radio" name="pizza_{$pizzen['PizzaId']}" value="im Ofen"  checked="checked" onclick="document.forms['baeckerform'].submit()">im Ofen</label>
                    <label><input class="radio" type="radio" name="pizza_{$pizzen['PizzaId']}" value="fertig"  onclick="document.forms['baeckerform'].submit()">fertig</label>
EOA;
                }
                if ($pizzen['PizzaStatus'] == "fertig") {
                    echo <<<EOB
                    <p>{$pizzen['PizzaName']}</p>
                    <label><input type="radio" class="radio" name="pizza_{$pizzen['PizzaId']}" value="bestellt"onclick="document.forms['baeckerform'].submit()">bestellt</label>
                    <label><input type="radio" class="radio" name="pizza_{$pizzen['PizzaId']}" value="im Ofen" onclick="document.forms['baeckerform'].submit()">im Ofen</label>
                    <label><input type="radio" class="radio" name="pizza_{$pizzen['PizzaId']}" value="fertig" checked="checked" onclick="document.forms['baeckerform'].submit()">fertig</label>
EOB;
                }
            }
adressfeld
        }
        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();

        if (isset($_POST) && !empty($_POST)) {
            foreach ($_POST as $fieldname => $value) {
                $start = substr($fieldname, 0, 5);
                if ($start == "pizza") {
                    $fieldname = substr($fieldname, 6);
                    if ($status_stmt = $this->_database->prepare("Update BestelltePizza set Status = (?) where BestelltePizza.PizzaID = $fieldname")) {
                        if ($status_stmt->bind_param("s", $value)) {
                            $status_stmt->execute();
                            $status_stmt->close();
                        }
                    }
                }
            }

        }
    }

    public static function main()
    {
        try {
            $page = new Baecker();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Baecker::main();