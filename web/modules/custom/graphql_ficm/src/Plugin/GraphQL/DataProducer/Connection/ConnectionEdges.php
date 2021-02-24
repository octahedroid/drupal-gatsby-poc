<?php

namespace Drupal\graphql_ficm_core\Plugin\GraphQL\DataProducer\Connection;

use Drupal\graphql\Plugin\DataProducerPluginCachingInterface;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;
use Drupal\graphql_ficm_core\Wrappers\EntityConnection;

/**
 * Fetches the edges from a connection
 *
 * @DataProducer(
 *   id = "connection_edges",
 *   name = @Translation("Connection edges"),
 *   description = @Translation("Returns the edges of a connection."),
 *   produces = @ContextDefinition("any",
 *     label = @Translation("Edges")
 *   ),
 *   consumes = {
 *     "connection" = @ContextDefinition("any",
 *       label = @Translation("QueryConnection")
 *     )
 *   }
 * )
 */
class ConnectionEdges extends DataProducerPluginBase implements DataProducerPluginCachingInterface
{

  /**
   * Resolves the request.
   *
   * @param \Drupal\social_graphql\GraphQL\EntityConnection $connection
   *   The connection to return the edges from.
   *
   * @return mixeduse Drupal\social_graphql\GraphQL\ConnectionInterface;
   *   The edges for the connection.
   */
  public function resolve(EntityConnection $connection)
  {
    return $connection->edges();
  }
}
