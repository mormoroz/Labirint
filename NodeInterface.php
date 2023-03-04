<?php

interface NodeInterface {
    /**
     * Connects the node to another $node.
     * A $distance, to balance the connection, can be specified.
     *
     * @param Node $node
     * @param integer $distance
     */
    public function connect(NodeInterface $node, int $distance = 1);

    /**
     * Returns the connections of the current node.
     *
     * @return Array
     */
    public function getConnections(): array;

    /**
     * Returns the identifier of this node.
     *
     * @return mixed
     */
    public function getId(): mixed;

    /**
     * Returns node's potential.
     *
     * @return integer
     */
    public function getPotential(): int;

    /**
     * Returns the node which gave to the current node its potential.
     *
     * @return Node
     */
    public function getPotentialFrom(): Node;

    /**
     * Returns whether the node has passed or not.
     *
     * @return boolean
     */
    public function isPassed(): bool;

    /**
     * Marks this node as passed, meaning that, in the scope of a graph, he
     * has already been processed in order to calculate its potential.
     */
    public function markPassed();

    /**
     * Sets the potential for the node, if the node has no potential or the
     * one it has is higher than the new one.
     *
     * @param integer $potential
     * @param Node $from
     * @return boolean
     */
    public function setPotential(int $potential, NodeInterface $from): bool;
}
