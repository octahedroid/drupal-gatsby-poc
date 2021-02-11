<?php

namespace Drupal\graphql_examples\Plugin\GraphQL\DataProducer\Field;

use DOMDocument;
use Drupal\Component\Utility\Html;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;

/**
 * Produces a field instance from an entity.
 *
 * Can be used instead of the property path when information about the field
 * item must be queryable. The property_path resolver always returns an array
 * which sometimes causes information loss.
 *
 * @DataProducer(
 *   id = "field_processed",
 *   name = @Translation("Field"),
 *   description = @Translation("Selects a field from an entity."),
 *   produces = @ContextDefinition("string",
 *     label = @Translation("Field")
 *   ),
 *   consumes = {
 *     "value" = @ContextDefinition("string",
 *       label = @Translation("Field name")
 *     )
 *   }
 * )
 */
class FieldProcessed extends DataProducerPluginBase
{

  /**
   * Processes the text field
   *
   * @param string $value
   *   The value to be processed
   *
   * @return string 
   *   A field item list if the field exists or null if the entity is not
   *   fieldable or doesn't have the requested field.
   */
  public function resolve(string $value)
  {
    $doc = Html::load($value);

    return  HTML::serialize($doc);
  }
}
