<?php

namespace Drupal\graphql_examples\Plugin\GraphQL\DataProducer\Field;

use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;
use Entity;

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
 *     "text" = @ContextDefinition("string",
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
   * @param object $text
   *   The value to be processed
   *
   * @return string 
   *   A field item list if the field exists or null if the entity is not
   *   fieldable or doesn't have the requested field.
   */
  public function resolve(object $text)
  {
    $raw = $text->value;
    $format = $text->format;

    return str_replace("\n", "", check_markup($raw, $format));
  }
}
