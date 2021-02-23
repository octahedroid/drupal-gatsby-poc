<?php

namespace Drupal\graphql_ficm_core\Plugin\GraphQL\DataProducer;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\graphql_ficm_core\Wrappers\QueryHelper;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;
use Drupal\graphql_ficm_core\Wrappers\EntityConnection;


/**
 * A generic Data Producer for entities.
 *
 * @DataProducer(
 *   id = "generic_query",
 *   name = @Translation("Query entities in the database"),
 *   produces = @ContextDefinition("any",
 *     label = @Translation("Connections to the specified type")
 *   ),
 *   consumes = {
 *     "type" = @ContextDefinition("string",
 *       label = @Translation("Entity type"),
 *     ),
 *     "limit" = @ContextDefinition("integer",
 *       label = @Translation("The amount of entries to fetch"),
 *       required = FALSE
 *       default_value = 10
 *     ),
 *     "from" = @ContextDefinition("string",
 *       label = @Translation("Cursor refernce point"),
 *       required = FALSE
 *     ),
 *     "reverseSort" = @ContextDefinition("boolean",
 *       label = @Translation("Ascending/Descending results"),
 *       required = FALSE,
 *       default_value = FALSE
 *     ),
 *     "reverseDirection" = @ContextDefinition("boolean",
 *       label = @Translation("Starting from the cursor, the next/previous N entries"),
 *       required = FALSE,
 *       default_value = FALSE
 *     ),
 *     "sortKey" = @ContextDefinition("string",
 *       label = @Translation("Sort key"),
 *       required = FALSE,
 *       default_value = "CREATED_AT"
 *     ),
 *   }
 * )
 */
class QueryUser extends DataProducerPluginBase
{

  /**
   * Resolves the request to the requested values.
   *
   * @param string $type
   *   The entity type
   * @param int $limit
   *   Fetch the first X results.
   * @param string|null $from
   *   Cursor to fetch results after.
   * @param bool $reverseSort
   *   Reverses the order of the data.
   * @param bool $reverseDirection
   *   Takes the previous/next entries starting from the cursor
   * @param string $sortKey
   *   Key to sort by.
   * @param \Drupal\Core\Cache\RefinableCacheableDependencyInterface $metadata
   *   Cacheability metadata for this request.
   *
   * @return \Drupal\social_graphql\GraphQL\ConnectionInterface
   *   An entity connection with results and data about the paginated results.
   *
   * @todo https://www.drupal.org/project/social/issues/3191622
   * @todo https://www.drupal.org/project/social/issues/3191637
   */
  public function resolve(string $type, int $limit, ?string $from, bool $reverseSort, bool $reverseDirection, string $sortKey, RefinableCacheableDependencyInterface $metadata)
  {
    $queryHelper = new QueryHelper($this->entityTypeManager, $type, $sortKey);
    $metadata->addCacheableDependency($queryHelper);

    $connection = new EntityConnection($queryHelper);
    $connection->setPagination($limit, $from, $reverseSort, $reverseDirection);
    return $connection;
  }
}
