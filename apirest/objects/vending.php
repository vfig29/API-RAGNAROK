<?php

class Vending{

    public $donoDaLoja;
    public $tituloDaLoja;
    public $mapaDaLoja;
    public $x;
    public $y;
    public $itensVendidos;

    public function __construct(){
        $this->itensVendidos = new ArrayObject();
    
    }

    public function addItem(Item $item){
        $this->itensVendidos->append($item);
        //$this->itensVendidos->offsetSet($item->idDoItem, $item);
        
        }
}
    
?>