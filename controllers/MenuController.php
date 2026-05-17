<?php
require_once __DIR__ . '/../models/MenuItem.php';

class MenuController
{
    private $menuItem;

    public function __construct()
    {
        $this->menuItem = new MenuItem();
    }

    public function index()
    {
        $items = $this->menuItem->getAllAvailableItems();

        require_once __DIR__ . '/../views/menu/index.php';
    }
}
?>