<?php

namespace Drupal\graphql_ficm_core\Plugin\GraphQL\Schema;

use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistry;
use Drupal\graphql\Plugin\GraphQL\Schema\SdlSchemaPluginBase;
use Drupal\graphql_ficm_core\Wrappers\QueryConnection;

/**
 * @Schema(
 *   id = "Base",
 *   name = "Base Resolvers"
 * )
 */
class BaseResolvers extends SdlSchemaPluginBase
{

  /**
   * {@inheritdoc}
   */
  public function getResolverRegistry()
  {
    $builder = new ResolverBuilder();
    $registry = new ResolverRegistry();

    $this->addQueryFields($registry, $builder);
    $this->addArticleFields($registry, $builder);
    $this->addPagesFields($registry, $builder);
    $this->addFormattedTextFields($registry, $builder);
    $this->addLibraryItem($registry, $builder);
    $this->addConnectionFields('Article', $registry, $builder);
    $this->addConnectionFields('Page', $registry, $builder);

    return $registry;
  }

  /**
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */


  protected function addArticleFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'Article',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'Article',
      'title',
      $builder->compose(
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent()),
        $builder->produce('uppercase')
          ->map('string', $builder->fromParent())
      )
    );

    $registry->addFieldResolver(
      'Article',
      'author',
      $builder->compose(
        $builder->produce('entity_owner')
          ->map('entity', $builder->fromParent()),
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent())
      )
    );

    $registry->addFieldResolver(
      'Article',
      'description',
      $builder->fromPath("entity:node", "field_description.value")
    );

    $registry->addFieldResolver(
      'Article',
      'descriptionRichText',
      $builder->fromPath("entity:node", "field_description_rich_text.value")
    );

    $registry->addFieldResolver(
      'Article',
      'content',
      $builder->produce('entity_reference_revisions', [
        'entity' => $builder->fromParent(),
        'field' => $builder->fromValue('field_content'),
      ])
    );
  }


  protected function addPagesFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'Page',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'Page',
      'title',
      $builder->compose(
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent()),
        $builder->produce('uppercase')
          ->map('string', $builder->fromParent())
      )
    );

    $registry->addFieldResolver(
      'Page',
      'content',
      $builder->produce('entity_reference_revisions', [
        'entity' => $builder->fromParent(),
        'field' => $builder->fromValue('field_content'),
      ])
    );
  }


  protected function addFormattedTextFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'FormattedText',
      'format',
      $builder->fromPath('text', 'format')
    );

    $registry->addFieldResolver(
      'FormattedText',
      'raw',
      $builder->fromPath('text', 'value')
    );

    $registry->addFieldResolver(
      'FormattedText',
      'processed',
      $builder->produce('field_processed')
        ->map('text', $builder->fromParent())
    );
  }


  protected function addLibraryItem(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'LibraryItem',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'LibraryItem',
      'label',
      $builder->produce('entity_label')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'LibraryItem',
      'paragraphs',
      $builder->produce('entity_reference_revisions', [
        'entity' => $builder->fromParent(),
        'field' => $builder->fromValue('paragraphs'),
      ])
    );
  }

  /**
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addQueryFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    // Connection fields.
    $registry->addFieldResolver(
      'Connection',
      'edges',
      $builder->produce('connection_edges')
        ->map('connection', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'Connection',
      'nodes',
      $builder->produce('connection_nodes')
        ->map('connection', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'Connection',
      'pageInfo',
      $builder->produce('connection_page_info')
        ->map('connection', $builder->fromParent())
    );

    // Edge fields.
    $registry->addFieldResolver(
      'Edge',
      'cursor',
      $builder->produce('edge_cursor')
        ->map('edge', $builder->fromParent())
    );
    $registry->addFieldResolver(
      'Edge',
      'node',
      $builder->produce('edge_node')
        ->map('edge', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'Query',
      'article',
      $builder->produce('entity_load')
        ->map('type', $builder->fromValue('node'))
        ->map('bundles', $builder->fromValue(['article']))
        ->map('id', $builder->fromArgument('id'))
    );

    $registry->addFieldResolver(
      'Query',
      'page',
      $builder->produce('entity_load')
        ->map('type', $builder->fromValue('node'))
        ->map('bundles', $builder->fromValue(['page']))
        ->map('id', $builder->fromArgument('id'))
    );

    $registry->addFieldResolver(
      'Query',
      'nodes',
      $builder->produce('generic_query')
        ->map('limit', $builder->fromArgument('limit'))
        ->map('from', $builder->fromArgument('from'))
        ->map('reverseSort', $builder->fromArgument('reverseSort'))
        ->map('reverseDirection', $builder->fromArgument('reverseDirection'))
        ->map('sortKey', $builder->fromArgument('sortKey'))
        ->map('type', $builder->fromValue('node'))
        ->map('bundle', $builder->fromValue('node'))
    );

    $registry->addFieldResolver(
      'Query',
      'pages',
      $builder->produce('generic_query')
        ->map('limit', $builder->fromArgument('limit'))
        ->map('from', $builder->fromArgument('from'))
        ->map('reverseSort', $builder->fromArgument('reverseSort'))
        ->map('reverseDirection', $builder->fromArgument('reverseDirection'))
        ->map('sortKey', $builder->fromArgument('sortKey'))
        ->map('type', $builder->fromValue('node'))
        ->map('bundle', $builder->fromValue('page'))
    );

    $registry->addFieldResolver(
      'Query',
      'articles',
      $builder->produce('generic_query')
        ->map('limit', $builder->fromArgument('limit'))
        ->map('from', $builder->fromArgument('from'))
        ->map('reverseSort', $builder->fromArgument('reverseSort'))
        ->map('reverseDirection', $builder->fromArgument('reverseDirection'))
        ->map('sortKey', $builder->fromArgument('sortKey'))
        ->map('type', $builder->fromValue('node'))
        ->map('bundle', $builder->fromValue('article'))
    );
  }

  /**
   * @param string $type
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addConnectionFields($type, ResolverRegistry $registry, ResolverBuilder $builder)
  {
    // Connection fields.
    $registry->addFieldResolver(
      $type . 'Connection',
      'edges',
      $builder->produce('connection_edges')
        ->map('connection', $builder->fromParent())
    );

    $registry->addFieldResolver(
      $type . 'Connection',
      'nodes',
      $builder->produce('connection_nodes')
        ->map('connection', $builder->fromParent())
    );

    $registry->addFieldResolver(
      $type . 'Connection',
      'pageInfo',
      $builder->produce('connection_page_info')
        ->map('connection', $builder->fromParent())
    );

    // Edge fields.
    $registry->addFieldResolver(
      $type . 'Edge',
      'cursor',
      $builder->produce('edge_cursor')
        ->map('edge', $builder->fromParent())
    );
    $registry->addFieldResolver(
      $type . 'Edge',
      'node',
      $builder->produce('edge_node')
        ->map('edge', $builder->fromParent())
    );
  }
}
