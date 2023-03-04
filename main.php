<?php





spl_autoload_register(function ($className)
{
    include $className. '.php';
});


/**
 * @throws Exception
 */
function printShortestPath($from_name, $to_name, $routes): void
{
    $graph = new Graph();
    foreach ($routes as $route) {
        $from = $route['from'];
        $to = $route['to'];
        if ($route['price'] == 0)
        {
            $price = count($routes) * 10;
        }
        else {
            $price = $route['price'];
        }
        if (! array_key_exists($from, $graph->getNodes())) {
            $from_node = new Node($from);
            $graph->add($from_node);
        } else {
            $from_node = $graph->getNode($from);
        }
        if (! array_key_exists($to, $graph->getNodes())) {
            $to_node = new Node($to);
            $graph->add($to_node);
        } else {
            $to_node = $graph->getNode($to);
        }
        $from_node->connect($to_node, $price);
    }

    $dijkstra = new Dijkstra($graph);
    $start_node = $graph->getNode($from_name);
    $end_node = $graph->getNode($to_name);
    $dijkstra->setStartingNode($start_node);
    $dijkstra->setEndingNode($end_node);
    echo "From: " . $start_node->getId() . "\n";
    echo "To: " . $end_node->getId() . "\n";
    echo "Route: " . $dijkstra->getLiteralShortestPath() . "\n";
    echo "Total: " . $dijkstra->getDistance() . "\n";
}

$routes = array();
echo"Input size of matrix [N][M]:".PHP_EOL;
echo "N= ";
$n = readline();
echo PHP_EOL."M= ";
$m = readline();
$id = 1;

for ($i = 0; $i < $n; $i++){
    for ($j = 0; $j < $m-1; $j++){
        echo "number of moves from A[", $i,"]","[", $j,"] (id = ",$id, ") to ", "A[", $i,"]","[", $j+1,"] (id = ",$id+1,"):".PHP_EOL;
        $price = readline();
        $routes[] = array('from'=>$id, 'to'=>($id+1), 'price'=>$price);
        $id++;
    }
    $id++;
}
$id = 1;
for ($j = 0; $j < $m; $j++){
    for ($i = 0; $i < $n-1; $i++){
        echo "number of moves from A[", $i,"]","[", $j,"] (id = ",$id, ") to ", "A[", $i+1,"]","[", $j,"] (id = ",$id+$m,"):".PHP_EOL;
        $price = readline();
        $routes[] = array('from'=>$id, 'to'=>($id+$m), 'price'=>$price);
        $routes[] = array('from'=>($id+$m), 'to'=>$id, 'price'=>$price);
        $id = $id + $m;
    }
    $id = $j+2;
}

echo "Input id of start point: ";
$start = readline();

echo "Input id of end point: ";
$end = readline();

//print_r($routes);
//$routes[] = array('from'=>'a', 'to'=>'b', 'price'=>0);
//$routes[] = array('from'=>'c', 'to'=>'d', 'price'=>3);
//$routes[] = array('from'=>'b', 'to'=>'c', 'price'=>2);
//$routes[] = array('from'=>'a', 'to'=>'d', 'price'=>9);
//$routes[] = array('from'=>'b', 'to'=>'d', 'price'=>3);

printShortestPath($start, $end, $routes);
