<?php

include_once '../config/database.php';
include_once '../objects/vending.php';
include_once '../objects/item.php';
class Model{

private $conn;
public $iditembuscado;
public $vendings;

public function __construct($db){
    $this->conn = $db;
    $this->vendings = new ArrayObject();
}

public function addVending(Vending $vending){
    $this->vendings->append($vending);
    //$this->vendings->offsetSet("Loja de " . $vending->donoDaLoja, $vending);
    }

// used when filling up the update product form
function readById(){

    // Primeira etapa, pegar todos as informações dos personagens que vendem o item buscado.
    $query = "SELECT `vendings`.id, `vendings`.map, `vendings`.x, `vendings`.y, `vendings`.title, `char`.name
    FROM `vendings`
    INNER JOIN `cart_inventory` ON `vendings`.char_id = `cart_inventory`.char_id
    INNER JOIN `char` ON `char`.char_id = `vendings`.char_id
    INNER JOIN `vending_items` ON `vendings`.id = `vending_items`.vending_id
    WHERE `cart_inventory`.nameid = " . $this->iditembuscado . " AND `cart_inventory`.id = `vending_items`.cartinventory_id
    GROUP BY `char`.name
    ORDER BY `char`.name ASC";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
    //Prestar atencao no bindparam em caso de erro
    $stmt->bindParam(1, $this->iditembuscado, PDO::PARAM_INT);
 
    // execute query
    $stmt->execute();
 

    //Segunda etapa, para cada vending de cada personagem, encontrar a listas de itens vendidos.
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $query2 = "SELECT `vending_items`.index, `cart_inventory`.nameid, `vending_items`.amount, `vending_items`.price, `cart_inventory`.refine, `cart_inventory`.card0, `cart_inventory`.card1, `cart_inventory`.card2, `cart_inventory`.card3,
        `cart_inventory`.option_id0, `cart_inventory`.option_val0,  `cart_inventory`.option_id1, `cart_inventory`.option_val1,  `cart_inventory`.option_id2, `cart_inventory`.option_val2,  `cart_inventory`.option_id3, `cart_inventory`.option_val3,  `cart_inventory`.option_id4, `cart_inventory`.option_val4
        FROM `vending_items`
        INNER JOIN `cart_inventory` ON `cart_inventory`.id = `vending_items`.cartinventory_id
        WHERE `vending_items`.vending_id = " . $row['id'] . " ORDER BY `vending_items`.index ASC";
        $stmt2 = $this->conn->prepare( $query2 );
        //Prestar atencao no bindparam em caso de erro
        $stmt->bindParam(1, $row['id'], PDO::PARAM_INT);
        $stmt2->execute();


        $vendingCriada = new Vending();
        $vendingCriada->donoDaLoja = $row['name'];
        $vendingCriada->tituloDaLoja = $row['title'];
        $vendingCriada->mapaDaLoja = $row['map'];
        $vendingCriada->x = $row['x'];
        $vendingCriada->y = $row['y'];
        //para cada item da lista instanciar um objeto e construir um array object.
        while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC))
        {
            $itemCriado = new Item($row2['nameid']);
            $itemCriado->indexDoItem = $row2['index'];
            $itemCriado->refinamento = $row2['refine'];
            $itemCriado->slot0 = $row2['card0'];
            $itemCriado->slot1 = $row2['card1'];
            $itemCriado->slot2 = $row2['card2'];
            $itemCriado->slot3 = $row2['card3'];
            $itemCriado->option_id0 = $row2['option_id0'];
            $itemCriado->option_val0 = $row2['option_val0'];
            $itemCriado->option_id1 = $row2['option_id1'];
            $itemCriado->option_val1 = $row2['option_val1'];
            $itemCriado->option_id2 = $row2['option_id2'];
            $itemCriado->option_val2 = $row2['option_val2'];
            $itemCriado->option_id3 = $row2['option_id3'];
            $itemCriado->option_val3 = $row2['option_val3'];
            $itemCriado->option_id4 = $row2['option_id4'];
            $itemCriado->option_val4 = $row2['option_val4'];
            $itemCriado->quantidadeDoItem = $row2['amount'];
            $itemCriado->precoDoItem = $row2['price'];
            $vendingCriada->addItem($itemCriado);
        }
        
        $this->addVending($vendingCriada); 
    }
}

function readAll(){

    // Primeira etapa, pegar todos as informações dos personagens que vendem o item buscado.
    $query = "SELECT `vendings`.id, `vendings`.map, `vendings`.x, `vendings`.y, `vendings`.title, `char`.name
    FROM `vendings`
    INNER JOIN `cart_inventory` ON `vendings`.char_id = `cart_inventory`.char_id
    INNER JOIN `char` ON `char`.char_id = `vendings`.char_id
    INNER JOIN `vending_items` ON `vendings`.id = `vending_items`.vending_id
    GROUP BY `char`.name
    ORDER BY `char`.name ASC";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
    //Prestar atencao no bindparam em caso de erro
    //$stmt->bindParam(1, $this->iditembuscado, PDO::PARAM_INT);
 
    // execute query
    $stmt->execute();
 

    //Segunda etapa, para cada vending de cada personagem, encontrar a listas de itens vendidos.
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $query2 = "SELECT `vending_items`.index, `cart_inventory`.nameid, `vending_items`.amount, `vending_items`.price, `cart_inventory`.refine, `cart_inventory`.card0, `cart_inventory`.card1, `cart_inventory`.card2, `cart_inventory`.card3,
        `cart_inventory`.option_id0, `cart_inventory`.option_val0,  `cart_inventory`.option_id1, `cart_inventory`.option_val1,  `cart_inventory`.option_id2, `cart_inventory`.option_val2,  `cart_inventory`.option_id3, `cart_inventory`.option_val3,  `cart_inventory`.option_id4, `cart_inventory`.option_val4
        FROM `vending_items`
        INNER JOIN `cart_inventory` ON `cart_inventory`.id = `vending_items`.cartinventory_id
        WHERE `vending_items`.vending_id = " . $row['id'] . " ORDER BY `vending_items`.index ASC";
        $stmt2 = $this->conn->prepare( $query2 );
        //Prestar atencao no bindparam em caso de erro
        $stmt->bindParam(1, $row['id'], PDO::PARAM_INT);
        $stmt2->execute();


        $vendingCriada = new Vending();
        $vendingCriada->donoDaLoja = $row['name'];
        $vendingCriada->tituloDaLoja = $row['title'];
        $vendingCriada->mapaDaLoja = $row['map'];
        $vendingCriada->x = $row['x'];
        $vendingCriada->y = $row['y'];
        //para cada item da lista instanciar um objeto e construir um array object.
        while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC))
        {
            $itemCriado = new Item($row2['nameid']);
            $itemCriado->indexDoItem = $row2['index'];
            $itemCriado->refinamento = $row2['refine'];
            $itemCriado->slot0 = $row2['card0'];
            $itemCriado->slot1 = $row2['card1'];
            $itemCriado->slot2 = $row2['card2'];
            $itemCriado->slot3 = $row2['card3'];
            $itemCriado->option_id0 = $row2['option_id0'];
            $itemCriado->option_val0 = $row2['option_val0'];
            $itemCriado->option_id1 = $row2['option_id1'];
            $itemCriado->option_val1 = $row2['option_val1'];
            $itemCriado->option_id2 = $row2['option_id2'];
            $itemCriado->option_val2 = $row2['option_val2'];
            $itemCriado->option_id3 = $row2['option_id3'];
            $itemCriado->option_val3 = $row2['option_val3'];
            $itemCriado->option_id4 = $row2['option_id4'];
            $itemCriado->option_val4 = $row2['option_val4'];
            $itemCriado->quantidadeDoItem = $row2['amount'];
            $itemCriado->precoDoItem = $row2['price'];
            $vendingCriada->addItem($itemCriado);
        }
        
        $this->addVending($vendingCriada); 
    }
}




}


?>