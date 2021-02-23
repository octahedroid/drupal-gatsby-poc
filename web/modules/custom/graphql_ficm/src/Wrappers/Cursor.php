<?php

namespace Drupal\graphql_ficm_core\Wrappers;

/**
 * Cursor for pagination
 * 
 * Must retain the information needed to paginate even if
 * the node it is pointing to is modified or deleted
 */

class Cursor
{
  /**
   * Drupal's entity type
   * 
   * @var string
   */
  protected string $internalType;

  /**
   * Drupal's entity ID
   * 
   * @var int
   */
  protected int $internalID;

  /**
   * The field that was used for the sorting
   * 
   * @var string
   */
  protected string $sortKey;

  /**
   * The value of the sort field
   * 
   * @var mixed
   */
  protected $sortKeyValue;

  public function __construct(string $internalType, int $internalID, string $sortKey, $sortKeyValue)
  {
    $this->internalType = $internalType;
    $this->internalID = $internalID;
    $this->sortKey = $sortKey;
    $this->sortKeyValue = $sortKeyValue;
  }

  /**
   * Converts the cursor to a string, using base64
   * 
   * @return string
   */
  public function toCursor(): string
  {
    return base64_encode(serialize($this));
  }

  /**
   * Takes a stringified cursor and returns an instance of the
   * Cursor class.
   * 
   * @param string $cursor
   * 
   * @return self|null
   */
  public static function fromCursor($cursor): ?self
  {
    $decoded_object = base64_decode($cursor, TRUE);

    if (!$decoded_object) {
      return NULL;
    }
    $deserialized_object = unserialize($decoded_object, ['allowed_classes' => [static::class]]);

    return $deserialized_object instanceof self ? $deserialized_object : NULL;
  }

  /**
   * Uses toCursor function to stringify object
   * 
   * @return string
   * 
   * @see toCursor()
   * 
   */
  public function __toString(): string
  {
    return $this->toCursor();
  }

  public function validFor(string $sortKey, string $internalType): bool
  {
    return $this->sortKey === $sortKey && $this->internalType === $internalType;
  }

  /**
   * Get the internal id
   * 
   * @return int
   */
  public function getInternalID(): int
  {
    return $this->internalID;
  }

  /**
   * Get the sort key value
   * 
   * @return mixed
   */
  public function getSortKeyValue()
  {
    return $this->sortKeyValue;
  }
}
