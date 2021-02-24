<?php

namespace Drupal\graphql_ficm_core\Wrappers;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use GraphQL\Deferred;
use Drupal\Core\Entity\Query\QueryInterface;
use GraphQL\Executor\Promise\Adapter\SyncPromise;
use Exception;

/**
 * Loads entities, depending on the type provided
 */
class QueryHelper
{

  /**
   * The Drupal entity type manager, provided from the plugin's
   * container
   * 
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * The field that is used for sorting the query results
   * 
   * @var string
   */
  protected $sortKey;

  /**
   * The field that is used for storing the entities' internal type 
   * 
   * @var string
   */
  protected $internalType;

  /**
   * TODO: better code
   */
  protected const IDMAP = array("user" => "uid", "node" => "nid", "taxonomy" => "tid");

  protected const SORTKEYMAP = array("user" => ["CREATED_AT" => "created"], "node" => ["CREATED_AT" => "created"]);

  /**
   * TODO: better code
   */
  protected static function getSORTMAP()
  {
    return [
      "CREATED_AT" => function ($entity) {
        return $entity->getCreatedTime();
      },
    ];
  }


  public function __construct(EntityTypeManagerInterface $entityTypeManager, string $internalType, string $sortKey)
  {
    $this->entityTypeManager = $entityTypeManager;
    $this->internalType = $internalType;
    $this->sortKey = $sortKey;
  }

  /**
   * 
   * Generates the query for a specific entity type
   * 
   * @return \Drupal\Core\Entity\Query\QueryInterface
   */
  public function getQuery(): QueryInterface
  {
    $query = $this->entityTypeManager
      ->getStorage($this->internalType)
      ->getQuery()
      ->currentRevision()
      ->accessCheck();

    if ($this->internalType === 'user') {
      $query->condition('uid', 0, '!='); //anonymous user
    }

    return $query;
  }

  /**
   * Get cursor object from a cursor string
   * 
   * @param string $cursor
   * 
   * @return \Drupal\social_graphql\Wrappers\Cursor|null
   */
  public function getCursorObject(string $cursor): ?Cursor
  {
    $cursorObject = Cursor::fromCursor($cursor);

    if (is_null($cursorObject) || !$cursorObject->validFor($this->sortKey, $this->internalType)) {
      return NULL;
    }

    return $cursorObject;
  }

  public function getLoaderPromise(array $result): SyncPromise
  {
    // Empty callback in there are no resulsts
    $callback = static function () {
      return [];
    };

    //Use buffer if there are results
    if (!empty($result)) {
      $buffer = \Drupal::service('graphql.buffer.entity');
      $callback = $buffer->add($this->internalType, array_values($result));
    }

    return new Deferred(
      function () use ($callback) {
        return array_map(
          function ($entity) {
            return new Edge(
              $entity,
              new Cursor($this->internalType, $entity->id(), $this->sortKey, $this->getSortValue($entity))
            );
          },
          $callback()
        );
      }
    );
  }

  /**
   * Get the name of the ID field for this particular
   * entity type
   * 
   * @return string 
   */
  public function getIdField(): string
  {
    // TODO: programmatically get this value
    return static::IDMAP[$this->internalType];
  }

  /**
   * Get the field name of the sort key
   */
  public function getSortField(): string
  {
    // TODO: better code
    return static::SORTKEYMAP[$this->internalType][$this->sortKey];
  }

  /**
   * Get the sort value for this particular connection
   * 
   * @param mixed $entity
   * 
   * @return mixed 
   */
  public function getSortValue($entity)
  {
    try {
      // TODO: better code
      return static::getSORTMAP()[$this->sortKey]($entity);
    } catch (Exception $e) {
      throw new \InvalidArgumentException("Unsupported sortKey for pagination '{$this->sortKey}'");
    }
  }
}
