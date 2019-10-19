<?php

require_once './Page.php';

class Fahrer extends Page
{
    protected function __construct()
    {
        parent::__construct();
    }

    protected function __destruct()
    {
        parent::__destruct();
    }

    protected function getPizzenProBestellung($bestellId)
    {
        $sql = "SELECT COUNT(*) as anzahlpizzen FROM BestelltePizza where BestelltePizza.fBestellungID = $bestellId";
        $result = $this->_database->query($sql);

        if (!$result) {
            trigger_error('Invalid query: ' . $this->_database->error);
        } else {
            $row = $result->fetch_all();
            $anzahl = $row;
        }

        $result->close();
        return $anzahl;
    }

    protected function getFertigePizzenProBestellung($bestellId)
    {
        $sql = "SELECT COUNT(*) as anzahlfertigepizzen FROM BestelltePizza where fBestellungID = $bestellId and (Status='fertig' or Status ='unterwegs' or Status ='geliefert')";
        $result = $this->_database->query($sql);

        if (!$result) {
            trigger_error('Invalid query: ' . $this->_database->error);
        } else {
            $row = $result->fetch_all();
            $anzahl = $row;
        }

        $result->close();
        return $anzahl;
    }

    protected function getBestellID($pizzaId)
    {
        $sql = "SELECT fBestellungID FROM BestelltePizza where PizzaID = $pizzaId";
        $result = $this->_database->query($sql);

        if (!$result) {
            trigger_error('Invalid query: ' . $this->_database->error);
        } else {
            $row = $result->fetch_all();
            $pizzaID = $row;
        }

        $result->close();
        return $pizzaID;
    }

    protected function getViewData()
    {
        $fertige_pizzen[] = array();

        if ($stmt = $this->_database->prepare("SELECT Angebot.PizzaName, BestelltePizza.PizzaID, BestelltePizza.Status, Bestellung.Adresse, BestelltePizza.fBestellungId FROM Bestellung JOIN BestelltePizza on BestelltePizza.fBestellungID = Bestellung.BestellungID JOIN Angebot on Angebot.PizzaNummer = BestelltePizza.fPizzaNummer where Status='fertig' or Status ='unterwegs' ")) {
            $stmt->execute();
            $stmt->bind_result($PizzaName, $PizzaID, $PizzaStatus, $Adresse, $BestellID);

            while ($stmt->fetch()) {
                $pizzen[] = array(
                    'PizzaName' => $PizzaName,
                    'PizzaId' => $PizzaID,
                    'PizzaStatus' => $PizzaStatus,
                    'Adresse' => $Adresse,
                    'BestellId' => $BestellID
                );
            }
            if (!empty($pizzen)) {
                foreach ($pizzen as $anzahl) {

                    $anzahlpizzenProbestellung = $this->getPizzenProBestellung($anzahl['BestellId']);
                    $anzahlfertigepizzen = $this->getFertigePizzenProBestellung($anzahl['BestellId']);

                    if (($anzahlfertigepizzen === $anzahlpizzenProbestellung) && ($anzahlfertigepizzen <> 0 && $anzahlpizzenProbestellung <> 0) && $anzahl['PizzaId']) {
                        $fertige_pizzen[] = array($anzahl['PizzaName'], $anzahl['PizzaId'], $anzahl['PizzaStatus'], $anzahl['Adresse'], $anzahl['BestellId']);
                    };

                }
            } else {
                print_r("Es wurden keine Pizzen bestellt!");
            }
        }
        if (!empty($fertige_pizzen)) {
            return $fertige_pizzen;
        } else {
            print_r("Es wurden keine Pizzen bestellt!");
        }
    }

    protected function generateView()
    {
        $headline = htmlspecialchars('Fahrer');

        header("Content-type: text/html; charset=UTF-8");

        $this->generatePageHeader($headline);

        echo "<meta http-equiv='refresh' content='5'>";

        $this->generatePageHeaderSecond($headline);

        echo "<section class='layout_padding'>";

        $fertige_pizzen = $this->getViewData();

        if (!empty($fertige_pizzen)) {

            $result = array();
            foreach ($fertige_pizzen as $element) {
                if ($element) {
                    $result[$element['3']][] = $element;
                }
            }
            echo "<form method=\"post\" id=\"fahrerform\">";

            foreach ($result as $adresse => $pizzeneineradresse) {
                echo "<p>Adresse: " . htmlspecialchars($adresse) . "</p><p>";
                foreach ($pizzeneineradresse as $einzelbestellung) {
                    echo $einzelbestellung['0'] . ", ";
                }
                echo "</p>";
                if ($einzelbestellung['2'] == "fertig") {
                    echo <<<EOA
                    <label><input type="radio" class="radio" name="pizza_{$einzelbestellung['1']}" value="fertig" checked="checked" onclick="document.forms['fahrerform'].submit()">fertig</label>
                    <label><input type="radio" class="radio" name="pizza_{$einzelbestellung['1']}" value="unterwegs" onclick="document.forms['fahrerform'].submit()">unterwegs</label>
                    <label><input type="radio" class="radio" name="pizza_{$einzelbestellung['1']}" value="geliefert" onclick="document.forms['fahrerform'].submit()">geliefert</label>
                    <br>
EOA;
                    continue;
                }
                if ($einzelbestellung['2'] == "unterwegs") {
                    echo <<<EOB
                    <label><input type="radio" class="radio" name="pizza_{$einzelbestellung['1']}" value="fertig" onclick="document.forms['fahrerform'].submit()">fertig</td></label>
                    <label><input type="radio" class="radio" name="pizza_{$einzelbestellung['1']}" value="unterwegs" checked="checked" onclick="document.forms['fahrerform'].submit()">unterwegs</label>
                    <label><input type="radio" class="radio" name="pizza_{$einzelbestellung['1']}" value="geliefert" onclick="document.forms['fahrerform'].submit()">geliefert</label>
                    <br>
EOB;
                    continue;
                }
                if ($einzelbestellung['2'] == "geliefert") {
                    echo <<<EOC
                    <label><input type="radio" class="radio" name="pizza_{$einzelbestellung['1']}" value="fertig" onclick="document.forms['fahrerform'].submit()">fertig</label>
                    <label><input type="radio" class="radio" name="pizza_{$einzelbestellung['1']}" value="unterwegs" onclick="document.forms['fahrerform'].submit()">unterwegs</label>
                    <label><input type="radio" class="radio" name="pizza_{$einzelbestellung['1']}" value="geliefert" checked="checked" onclick="document.forms['fahrerform'].submit()">geliefert</label>
                    <br>
EOC;
                    continue;
                }
            }
            echo "</form>";
            echo "</section>";
        }
        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();

        if (isset($_POST) && !empty($_POST)) {
            foreach ($_POST as $fieldname => $value) {
                // starts with "pizza"
                $start = substr($fieldname, 0, 5);
                if ($start == "pizza") {
                    // remove "pizza"
                    $fieldname = substr($fieldname, 6);
                    $bestellId = $this->getBestellID($fieldname);

                    foreach ($bestellId as $_id) {
                        if ($status_stmt = $this->_database->prepare("Update BestelltePizza set Status = (?) where BestelltePizza.fBestellungID = $_id[0]")) {
                            if ($status_stmt->bind_param("s", $value)) {
                                $status_stmt->execute();
                                $status_stmt->close();
                            }
                        }
                    }
                }
            }
            header('Location: fahrer.php');
        }
    }

    public static function main()
    {
        try {
            $page = new Fahrer();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Fahrer::main();
