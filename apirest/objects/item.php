<?php

class Item{

    public $indexDoItem;
    public $idDoItem;
    public $refinamento;
    public $slot0;
    public $slot1;
    public $slot2;
    public $slot3;
    public $option_id0;
    public $option_val0;
    public $option_id1;
    public $option_val1;
    public $option_id2;
    public $option_val2;
    public $option_id3;
    public $option_val3;
    public $option_id4;
    public $option_val4;
    public $quantidadeDoItem;
    public $precoDoItem;

    public function __construct($idDoItem){
        $this->idDoItem = $idDoItem;
    }

}
?>