<?php

class Node implements NodeInterface {
    protected mixed $id;
    protected int $potential;
    protected Node $potentialFrom;
    protected array $connections;
    protected bool $passed;

    /**
     * Instantiates a new node, requiring a ID to avoid collisions.
     *
     * @param mixed $id
     */
    public function __construct(mixed $id) {
        $this->id = $id;
        $this->potential = 0;
        $this->connections = [];
        $this->passed = false;
    }

    /**
     * Connects the node to another $node.
     * A $distance, to balance the connection, can be specified.
     *
     * @param Node $node
     * @param integer $distance
     */
    public function connect(NodeInterface $node, int $distance = 1) {
        $this->connections[$node->getId()] = $distance;
    }

    /**
     * Returns the distance to the node.
     *
     * @param NodeInterface $node
     * @return Array
     */
    public function getDistance(NodeInterface $node): array
    {
        return $this->connections[$node->getId()];
    }

    /**
     * Returns the connections of the current node.
     *
     * @return Array
     */
    public function getConnections(): array
    {
        return $this->connections;
    }

    /**
     * Returns the identifier of this node.
     *
     * @return mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * Returns node's potential.
     *
     * @return integer
     */
    public function getPotential(): int
    {
        return $this->potential;
    }

    /**
     * Returns the node which gave to the current node its potential.
     *
     * @return Node
     */
    public function getPotentialFrom(): Node
    {
        return $this->potentialFrom;
    }

    /**
     * Returns whether the node has passed or not.
     *
     * @return bool
     */
    public function isPassed(): bool
    {
        return $this->passed;
    }

    /**
     * Marks this node as passed, meaning that, in the scope of a graph, he
     * has already been processed in order to calculate its potential.
     */
    public function markPassed() {
        $this->passed = true;
    }

    /**
     * Sets the potential for the node, if the node has no potential or the
     * one it has is higher than the new one.
     *
     * @param integer $potential
     * @param Node $from
     * @return bool
     */
    public function setPotential(int $potential, NodeInterface $from): bool
    {
        if (! $this->getPotential() || $potential < $this->getPotential()) {
            $this->potential = $potential;
            $this->potentialFrom = $from;
            return true;
        }
        return false;
    }
}