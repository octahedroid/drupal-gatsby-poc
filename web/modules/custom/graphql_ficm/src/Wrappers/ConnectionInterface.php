<?php

namespace Drupal\graphql_ficm_core\Wrappers;

use GraphQL\Executor\Promise\Adapter\SyncPromise;

/**
 * Common interface for connections
 */
interface ConnectionInterface
{

  /**
   * Sets the paginations parameters before querying the data.
   * 
   * @param int|null $limit
   *      Limit to the first N results
   * 
   * @param string|null $from
   *      Cursor reference
   * 
   * @param bool $reverseSort
   *      Reverse the sorting order
   * 
   * @param bool $reverseDirection
   *      Instead of taking the next N nodes after the cursor, 
   *      take the first N nodes before the cursor
   * 
   * @return $this
   * 
   */
  public function setPagination(int $limit, ?string $from, bool $reverseSort, bool $reverseDirection);


  /**
   * Get additional info from the connection
   * 
   * @return \GraphQL\Executor\Promise\Adapter\SyncPromise
   */
  public function pageInfo(): SyncPromise;


  /**
   * Get the edges from the connection
   * 
   * @return \GraphQL\Executor\Promise\Adapter\SyncPromise
   */
  public function edges(): SyncPromise;


  /**
   * Bypass edge information and get the node information directly
   * from the connection
   */
  public function nodes(): SyncPromise;
}
