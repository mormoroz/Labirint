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
$routes[] = array('from'=>'a', 'to'=>'b', 'price'=>0);
$routes[] = array('from'=>'c', 'to'=>'d', 'price'=>3);
$routes[] = array('from'=>'b', 'to'=>'c', 'price'=>2);
$routes[] = array('from'=>'a', 'to'=>'d', 'price'=>9);
$routes[] = array('from'=>'b', 'to'=>'d', 'price'=>3);

printShortestPath('a', 'd', $routes);