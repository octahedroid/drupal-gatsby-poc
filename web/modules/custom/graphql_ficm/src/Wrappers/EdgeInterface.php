<?php

namespace Drupal\graphql_ficm_core\Wrappers;

/**
 * Interface for edges 
 */
interface EdgeInterface
{
  /**
   * Get the cursor for the particular edge
   */
  public function getCursor(): string;

  /**
   * Get the node value for the edge
   */
  public function getNode();
}
