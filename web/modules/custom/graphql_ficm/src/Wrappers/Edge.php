<?php

namespace Drupal\graphql_ficm_core\Wrappers;

/**
 * Base class for Edges, according to GraphQL specifications
 */
class Edge implements EdgeInterface
{
  /**
   * The entity value for this edge
   * 
   * @var mixed
   */
  protected $node;

  /**
   * The cursor for this edge
   * 
   * @var string
   */
  protected string $cursor;

  /**
   * @param mixed $node
   * 
   * @param string $cursor
   */
  public function __construct($node, string $cursor)
  {
    $this->node = $node;
    $this->cursor = $cursor;
  }

  /**
   * {@inheritdoc}
   */
  public function getCursor(): string
  {
    return $this->cursor;
  }

  /**
   * {@inheritdoc}
   */
  public function getNode()
  {
    return $this->node;
  }
}
