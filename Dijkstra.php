<?php

class Dijkstra {
    protected Node $startingNode;
    protected Node $endingNode;
    protected Graph $graph;
    protected array $paths;
    protected bool $solution;

    /**
     * Instantiates a new algorithm, requiring a graph to work with.
     *
     * @param Graph $graph
     */
    public function __construct(Graph $graph) {
        $this->graph = $graph;
        $this->paths = array();
        $this->solution = false;
    }

    /**
     * Returns the distance between the starting and the ending point.
     *
     * @return integer
     * @throws Exception
     */
    public function getDistance(): int
    {
        if (! $this->isSolved()) {
            throw new Exception("Cannot calculate the distance of a non-solved algorithm:\nDid you forget to call ->solve()?");
        }
        return $this->getEndingNode()->getPotential();
    }

    /**
     * Gets the node which we are pointing to.
     *
     * @return Node
     */
    public function getEndingNode(): Node
    {
        return $this->endingNode;
    }

    /**
     * Returns the solution in a human-readable style.
     *
     * @return string
     * @throws Exception
     */
    public function getLiteralShortestPath(): string
    {
        $path = $this->solve();
        $literal = '';
        foreach ( $path as $p ) {
            $literal .= "{$p->getId()} - ";
        }
        $this->solution = true;
        return substr($literal, 0, strlen($literal) - 3);
    }

    /**
     * Reverse-calculates the shortest path of the graph thanks the potentials
     * stored in the nodes.
     *
     * @return Array
     */
    public function getShortestPath(): array
    {
        $path = array();
        $node = $this->getEndingNode();
        while ( $node->getId() != $this->getStartingNode()->getId()) {
            $path[] = $node;
            $node = $node->getPotentialFrom();
        }
        $path[] = $this->getStartingNode();
        return array_reverse($path);
    }

    /**
     * Retrieves the node which we are starting from to calculate the shortest path.
     *
     * @return Node
     */
    public function getStartingNode(): Node
    {
        return $this->startingNode;
    }

    /**
     * Sets the node which we are pointing to.
     *
     * @param Node $node
     */
    public function setEndingNode(Node $node): void
    {
        $this->endingNode = $node;
    }

    /**
     * Sets the node which we are starting from to calculate the shortest path.
     *
     * @param Node $node
     */
    public function setStartingNode(Node $node): void
    {
        $this->paths[] = array($node);
        $this->startingNode = $node;
    }

    /**
     * Solves the algorithm and returns the shortest path as an array.
     *
     * @return array
     * @throws Exception
     */
    public function solve(): array
    {
        if (! $this->getStartingNode() || ! $this->getEndingNode()) {
            throw new Exception("Cannot solve the algorithm without both starting and ending nodes");
        }
        $this->calculatePotentials($this->getStartingNode());
        return $this->getShortestPath();
    }

    /**
     * Recursively calculates the potentials of the graph, from the
     * starting point you specify with ->setStartingNode(), traversing
     * the graph due to Node's $connections attribute.
     *
     * @param Node $node
     * @throws Exception
     */
    protected function calculatePotentials(Node $node): void
    {
        $connections = $node->getConnections();
        $connections = array_diff($connections, array(NULL));
        $sorted = array_flip($connections);
        krsort($sorted);
        foreach ( $connections as $id => $distance ) {
            $v = $this->getGraph()->getNode($id);
            $v->setPotential($node->getPotential() + $distance, $node);
            foreach ($this->getPaths() as $path) {
                $count = count($path);
                if ($path[$count - 1]->getId() === $node->getId()) {
                    $this->paths[] = array_merge($path, array($v));
                }
            }
        }
        $node->markPassed();
        // Get loop through the current node's nearest connections
        // to calculate their potentials.
        foreach ( $sorted as $id ) {
            $node = $this->getGraph()->getNode($id);
            if (! $node->isPassed()) {
                $this->calculatePotentials($node);
            }
        }
    }

    /**
     * Returns the graph associated with this algorithm instance.
     *
     * @return Graph
     */
    protected function getGraph(): Graph
    {
        return $this->graph;
    }

    /**
     * Returns the possible paths registered in the graph.
     *
     * @return Array
     */
    protected function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * Checks whether the current algorithm has been solved or not.
     *
     * @return boolean
     */
    protected function isSolved(): bool
    {
        return $this->solution;
    }
}