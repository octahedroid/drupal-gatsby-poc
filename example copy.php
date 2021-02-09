<?php

namespace Drupal\graphql_examples\Plugin\GraphQL\Schema;

use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistry;
use Drupal\graphql\Plugin\GraphQL\Schema\SdlSchemaPluginBase;
use Drupal\graphql_examples\Wrappers\QueryConnection;

/**
 * @Schema(
 *   id = "example",
 *   name = "Example schema"
 * )
 */
class ExampleSchema extends SdlSchemaPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getResolverRegistry() {
    $builder = new ResolverBuilder();
    $registry = new ResolverRegistry();

    $this->addQueryFields($registry, $builder);
    $this->addArticleFields($registry, $builder);

    // Re-usable connection type fields.
    $this->addConnectionFields('ArticleConnection', $registry, $builder);

    return $registry;
  }

  /**
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addArticleFields(ResolverRegistry $registry, ResolverBuilder $builder) {
    $registry->addFieldResolver('Article', 'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('Article', 'title',
      $builder->compose(
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent()),
        $builder->produce('uppercase')
          ->map('string', $builder->fromParent())
      )
    );

    $registry->addFieldResolver('Article', 'author',
      $builder->compose(
        $builder->produce('entity_owner')
          ->map('entity', $builder->fromParent()),
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent())
      )
    );





    $registry->addFieldResolver('Article', 'description',
    $builder->produce('field')
      ->map('field', $builder->fromValue('field_description'))
      ->map('entity', $builder->fromParent())
  );





    $registry->addFieldResolver('Article', 'summaryName',
    $builder->produce('entity_label')
      ->map('entity', $builder->fromParent())
      ->map('field', $builder->fromValue('field_summary'))
  );
    $registry->addFieldResolver('Article', 'summaryDescription',
    $builder->produce('entity_description')
      ->map('entity', $builder->fromParent())
      ->map('field', $builder->fromValue('field_summary'))
  );
    $registry->addFieldResolver('Article', 'summaryBundle',
    $builder->produce('entity_bundle')
      ->map('entity', $builder->fromParent())
      ->map('field', $builder->fromValue('field_summary'))
  );


    $registry->addFieldResolver('Article', 'summaryRendered',
    $builder->produce('entity_load')
    ->map('type', $builder->fromValue('node'))
    ->map('bundles', $builder->fromValue(['article']))
  );



    $registry->addFieldResolver('Article', 'summaryPublished',
    $builder->produce('entity_published')
      ->map('entity', $builder->fromParent())
      ->map('field', $builder->fromValue('field_summary'))
  );
    $registry->addFieldResolver('Article', 'summaryContext',
    $builder->produce('context')
      ->map('entity', $builder->fromParent())
      ->map('field', $builder->fromValue('field_summary'))
  );



    $registry->addFieldResolver('Article', 'summaryEntityLoad',
    $builder->produce('entity_load')
      ->map('type', $builder->fromValue("article"))
      ->map('id', $builder->fromValue("article"))
  );
    $registry->addFieldResolver('Article', 'summaryPath',
    $builder->produce('property_path')
      ->map('entity', $builder->fromParent())
      ->map('field', $builder->fromValue('field_summary'))
      ->map('value', $builder->fromParent())
  );
  }

  /**
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addQueryFields(ResolverRegistry $registry, ResolverBuilder $builder) {
    $registry->addFieldResolver('Query', 'article',
      $builder->produce('entity_load')
        ->map('type', $builder->fromValue('node'))
        ->map('bundles', $builder->fromValue(['article']))
        ->map('id', $builder->fromArgument('id'))
    );

    $registry->addFieldResolver('Query', 'articles',
      $builder->produce('query_articles')
        ->map('offset', $builder->fromArgument('offset'))
        ->map('limit', $builder->fromArgument('limit'))
    );
  }

  /**
   * @param string $type
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addConnectionFields($type, ResolverRegistry $registry, ResolverBuilder $builder) {
    $registry->addFieldResolver($type, 'total',
      $builder->callback(function (QueryConnection $connection) {
        return $connection->total();
      })
    );

    $registry->addFieldResolver($type, 'items',
      $builder->callback(function (QueryConnection $connection) {
        return $connection->items();
      })
    );
  }

}
