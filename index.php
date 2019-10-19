<?php

require_once './Page.php';

class Index extends Page
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

    }

    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('Pizzaservice');
        $this->generatePageHeaderSecond('Pizzaservice');
        echo <<< EOT
        <img src="images/pizza-logo.png" alt="logo" id="logoMainPage">
        <h1 class="headerCenter">Herzlich Willkommen</h1>
        <p class="textCenter"><br>beim Pizzaservice<br>von Marius und Ingo</p>
EOT;
        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();
    }

    public static function main()
    {
        try {
            $page = new Index();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Index::main();