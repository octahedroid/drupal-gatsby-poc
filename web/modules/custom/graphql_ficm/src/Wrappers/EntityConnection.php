<?php

namespace Drupal\graphql_ficm_core\Wrappers;

use Drupal\graphql_ficm_core\Wrappers\EdgeInterface;
use GraphQL\Error\UserError;
use GraphQL\Executor\Promise\Adapter\SyncPromise;
use Drupal\graphql_ficm_core\Wrappers\QueryHelper;

class EntityConnection implements ConnectionInterface
{
  /**
   * Maximun number of nodes that can be fetched from the connection
   */
  protected const MAX_LIMIT = 1000;


  /**
   * The provided query for the connection
   * @var Drupal\graphql_ficm_core\Wrappers\QueryHelper
   */
  protected $queryHelper;

  /**
   * Fetch N results
   */
  protected $limit = NULL;

  /**
   * Cursor reference point for the results
   */
  protected $from = NULL;

  /**
   * Reverse the sorting order of the requests
   */
  protected $reverseSort = FALSE;

  /**
   * Reverse the direction of nodes to take starting from 
   * the cursor (next N nodes or previous N nodes)
   */
  protected $reverseDirection = FALSE;

  /**
   * The result set of the conection
   */
  protected $result;

  /**
   * Create a new Entity Connection with pagination
   * 
   * @param \Drupal\graphql_ficm_core\Wrappers\QueryHelper
   */
  public function __construct(QueryHelper $queryHelper)
  {
    $this->queryHelper = $queryHelper;
  }

  /**
   * {@inheritdoc}
   */
  public function setPagination(int $limit, ?string $from, bool $reverseSort, bool $reverseDirection)
  {
    // Disallow changing pagination after a query has been performed because the
    // way we treat the results depends on it.
    if ($this->hasResult()) {
      throw new \RuntimeException("Cannot change pagination after a query for a connection has been executed.");
    }

    $this->validatePagination($limit, static::MAX_LIMIT);
    $this->limit = $limit;
    $this->from = $from;
    $this->reverseSort = $reverseSort;
    $this->reverseDirection = $reverseDirection;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function pageInfo(): SyncPromise
  {
    return $this->getResult()->then(function ($edges) {
      // If we don't have any results then we won't have any other pages either.
      if (empty($edges)) {
        return [
          'hasNextPage' => FALSE,
          'hasPreviousPage' => FALSE,
          'startCursor' => NULL,
          'endCursor' => NULL,
        ];
      }

      return [
        'hasNextPage' => TRUE,
        'hasPreviousPage' => TRUE,
        'startCursor' => NULL,
        'endCursor' => NULL,
      ];
    });
  }

  /**
   * Whether this connection has a result.
   *
   * @return bool
   *   
   */
  protected function hasResult(): bool
  {
    return isset($this->result);
  }

  /**
   * Get the results for this connection. Once run, it
   * will always return the same promise
   * 
   * @return \GraphQL\Executor\Promise\Adapter\SyncPromise
   */
  protected function getResult(): SyncPromise
  {
    if (!$this->hasResult()) {
      $this->result = $this->execute();
    }

    return $this->result;
  }

  /**
   * Function that removes any extra entries that should not be included in the result
   * of the query.
   * 
   * @return \GraphQL\Executor\Promise\Adapter\SyncPromise
   */
  protected function getTrimmedResult(): SyncPromise
  {
    return $this->getResult()->then(function ($edges) {
      // Remove the extra result we fetch for pagination
      $edges = array_slice($edges, 0, $this->limit);
      return $edges;
    });
  }

  /**
   * Perform certain validations on the pagination
   * parameters
   * 
   * @param int $limit
   * 
   * @param int $max_limit
   */
  protected function validatePagination(int $limit, int $max_limit)
  {
    if ($limit <= 0) {
      throw new UserError("limit must be positive integer");
    }
    if ($limit > $max_limit) {
      throw new UserError("limit can not be larger than " . $max_limit);
    }
  }


  /**
   * Execute the query and fetch the entitities
   * 
   * @return \GraphQL\Executor\Promise\Adapter\SyncPromise
   */
  protected function execute(): SyncPromise
  {
    $query = $this->queryHelper->getQuery();
    $sortField = $this->queryHelper->getSortField();
    $idField = $this->queryHelper->getIdField();
    $limit = $this->limit;

    // Since we can not only reverse the direction of the sort (e.g. sort posts by oldest or newest first),
    // but also reverse the direction of the post to fetch starting from the cursor (e.g. from the cursor, the
    // previous or next N posts), we have to set this the direction of the query based on these conditions

    $queryOrder = $this->reverseDirection xor $this->reverseSort ? "DESC" : "ASC";

    // Logic handling the conditions based on a the optional cursor provided
    $cursor = $this->from;
    if (!is_null($cursor)) {
      $cursorObject = $this->queryHelper->getCursorObject($cursor);
      if (is_null($cursorObject)) {
        throw new UserError("invalid cursor '${$cursor}'");
      }

      $paginationCondition = $query->orConditionGroup();

      // Due to same reasons as the $queryOrder variable, the operator que have to use for the 
      // id field
      $conditionOperator = $this->reverseDirection xor $this->reverseSort ? '<' : ">";

      $cursorValue = $cursorObject->getSortKeyValue();

      $paginationCondition->condition($sortField, $cursorValue, $conditionOperator);

      // If the sort field is not the ID, there is a chance values may repeat
      // In those cases, we should include the entries with the same sort key
      // value as long as their ID follows the query direction from the cursor
      if ($sortField !== $idField) {
        $paginationCondition->condition(
          $query->andConditionGroup()
            ->condition($sortField, $cursorValue, '=')
            ->condition($idField, $cursorObject->getInternalID(), $conditionOperator)
        );
      }

      $query->condition($paginationCondition);
    }


    $query->range(0, $limit + 1);
    $query->sort($sortField, $queryOrder);
    // To ensure a consistent sorting for duplicate fields we add a secondary
    // sort based on the ID.
    if ($sortField !== $idField) {
      $query->sort(
        $idField,
        $queryOrder
      );
    }

    // Fetch results
    $result = $query->execute();

    // Due to the way the SQL query is built, we have to compensate the order
    // of the results if we set reverseDirection to true
    if ($this->reverseDirection) {
      $result = array_reverse($result);
    }
    return $this->queryHelper->getLoaderPromise($result);
  }

  /**
   * {@inheritdoc}
   */
  public function edges(): SyncPromise
  {
    return $this->getTrimmedResult();
  }

  /**
   * {@inheritdoc}
   */
  public function nodes(): SyncPromise
  {
    return $this->getTrimmedResult()
      ->then(
        static function ($edges) {
          return array_map(
            static function (EdgeInterface $edge) {
              return $edge->getNode();
            },
            $edges
          );
        }
      );
  }
}
