<?php

require_once 'Page.php';

class Bestellung extends Page
{
    protected $pizzen;

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
        $pizzen = array();

        if ($stmt = $this->_database->prepare("SELECT PizzaNummer, PizzaName, Bilddatei, Preis FROM Angebot ORDER BY PizzaNummer")) {
            $stmt->execute();
            $stmt->bind_result($PizzaNummer, $PizzaName, $Bilddatei, $Preis);
            // read selected records into result array
            while ($stmt->fetch()) {
                $pizzen[] = array(
                    'PizzaNummer' => $PizzaNummer,
                    'PizzaName' => $PizzaName,
                    'Bilddatei' => $Bilddatei,
                    'Preis' => $Preis
                );
            }
            $stmt->close();
        }
        return $pizzen;
    }

    protected function generateView()
    {
        $pizzen = $this->getViewData();

        $this->generatePageHeader('Bestellung');
        $this->generatePageHeaderSecond('Bestellung');

        echo "<section class='layout_padding'>";

        foreach ($pizzen as $pizza) {
            $pizzaName = $pizza['PizzaName'];
            $pizzaNummer = $pizza['PizzaNummer'];
            $pizzaPreis = $pizza['Preis'];
            echo <<<EOD
            <ul class="pizzaList">
                <li><b>{$pizza['PizzaName']}</b></li>
                <li>{$pizza['Preis']} €</li>
                <li><img src="{$pizza['Bilddatei']}" class="pizzaimg" alt="{$pizza['PizzaName']}" onclick="pizzaOnClick('$pizzaName', '$pizzaNummer', '$pizzaPreis')"></li> 
            </ul>
EOD;
        }
        echo <<<EOF
        
        <div class="orderingarea">
        <h2>Warenkorb</h2>
            <form target="_blank" method="post">
                <select name="pizzenAuswahl[]" multiple tabindex="5" id="shoppingcart">
                </select>
                <div id="preisDiv"></div>   
                <input value="" type="text" placeholder="Ihre Adresse" tabindex="1" name="adresse" id="addressfield" oninput="isBestellungErlaubt()"><br>
                <button type="button" tabindex="2" accesskey="a" onclick="deleteAll()">Alle löschen</button>
                <button type="button" tabindex="3" accesskey="d" onclick="deleteAllSelected()">Auswahl löschen</button>
                <button type="submit" tabindex="4" accesskey="b" onclick="selectAllPizzaInWarenkorb()"  id="orderingbutton" disabled >Bestellen</button>
            </form>
        </div>
        </section>
EOF;
        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();

        if (isset($_POST['pizzenAuswahl']) && !empty($_POST['pizzenAuswahl']) && is_array($_POST['pizzenAuswahl'])) {
            $pizza_liste = $_POST['pizzenAuswahl'];

            if (isset($_POST['adresse'])) {
                $adr = $_POST['adresse'];
            }

            if ($bestellung_stmt = $this->_database->prepare("INSERT INTO Bestellung (Adresse) values (?)")) {
                if ($bestellung_stmt->bind_param("s", $adr)) {
                    $bestellung_stmt->execute();
                    if (!isset($_SESSION["BestellID"])) {

                        $bestellung_id = $bestellung_stmt->insert_id;
                    } else {
                        $bestellung_id = $_SESSION["BestellID"];
                    }
                    $bestellung_stmt->close();
                }
                foreach ($pizza_liste as $pizza_id) {
                    if ($bestelltePizza_stmt = $this->_database->prepare("INSERT INTO BestelltePizza (fBestellungID, fPizzaNummer) values (?,?)")) {
                        if ($bestelltePizza_stmt->bind_param("ii", $bestellung_id, $pizza_id)) {
                            $bestelltePizza_stmt->execute();
                            $bestelltePizza_stmt->close();
                        }
                    }
                }
            }

            $_SESSION["BestellID"] = $bestellung_id;

            header('Location: kunde.php');
        }
    }

    public static function main()
    {
        try {
            session_start();
            $page = new Bestellung();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Bestellung::main();
