<?php

namespace Drupal\graphql_examples\Plugin\GraphQL\Schema;

use Drupal\Core\Plugin\Context\ContextDefinition;
use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistry;
use Drupal\graphql\Plugin\GraphQL\Schema\SdlSchemaPluginBase;
use Drupal\graphql_examples\Wrappers\QueryConnection;
use GraphQL\Error\Error;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * @Schema(
 *   id = "example",
 *   name = "Example schema"
 * )
 */
class ExampleSchema extends SdlSchemaPluginBase
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
    $this->addTextParagraphFields($registry, $builder);
    $this->addHeroTextParagraphFields($registry, $builder);
    $this->addStaticParagraphFields($registry, $builder);
    $this->addCodeSnippetParagraphFields($registry, $builder);
    $this->addCardParagraphFields($registry, $builder);
    $this->addCardImageGroupParagraphFields($registry, $builder);
    $this->addCtaParagraphFields($registry, $builder);
    $this->addCardGroupParagraphFields($registry, $builder);
    $this->addCardImageParagraphFields($registry, $builder);

    $this->addConnectionFields('ArticleConnection', $registry, $builder);
    $this->addConnectionFields('PageConnection', $registry, $builder);

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


  protected function addTextParagraphFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'Text',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'Text',
      'centered',
      $builder->fromPath("entity:node", "field_centered.value")
    );

    $registry->addFieldResolver(
      'Text',
      'intro',
      $builder->fromPath("entity:node", "field_intro.value")
    );

    $registry->addFieldResolver(
      'Text',
      'text',
      $builder->fromPath("entity:node", "field_text.value")
    );

    $registry->addFieldResolver(
      'Text',
      'title',
      $builder->fromPath("entity:node", "field_title.value")
    );

    $registry->addFieldResolver(
      'Text',
      'titleAs',
      $builder->fromPath("entity:node", "field_title_as.value")
    );

  }


  protected function addHeroTextParagraphFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'HeroText',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'HeroText',
      'text',
      $builder->fromPath("entity:node", "field_text.value")
    );

    $registry->addFieldResolver(
      'HeroText',
      'title',
      $builder->fromPath("entity:node", "field_title.value")
    );
  }


  protected function addStaticParagraphFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'Static',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'Static',
      'component',
      $builder->fromPath("entity:node", "field_component.value")
    );
  }


  protected function addCodeSnippetParagraphFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'CodeSnippet',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'CodeSnippet',
      'code',
      $builder->fromPath("entity:node", "field_code.value")
    );

    $registry->addFieldResolver(
      'CodeSnippet',
      'hideNumbers',
      $builder->fromPath("entity:node", "field_hide_numbers.value")
    );

    $registry->addFieldResolver(
      'CodeSnippet',
      'language',
      $builder->fromPath("entity:node", "field_language.value")
    );

    $registry->addFieldResolver(
      'CodeSnippet',
      'text',
      $builder->fromPath("entity:node", "field_text.value")
    );

    $registry->addFieldResolver(
      'CodeSnippet',
      'theme',
      $builder->fromPath("entity:node", "field_theme.value")
    );
  }


  protected function addCardParagraphFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'Card',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('Card', 'image',
      $builder->compose(
        $builder->produce('property_path')
          ->map('type', $builder->fromValue('entity:node'))
          ->map('value', $builder->fromParent())
          ->map('path', $builder->fromValue('field_image.target_id')),
        $builder->produce('entity_load')
          ->map('type', $builder->fromValue('file'))
          ->map('id', $builder->fromParent()),
        $builder->produce('image_derivative')
          ->map('entity', $builder->fromParent())
          ->map('style', $builder->fromValue('large')),
      )
    );

    $registry->addFieldResolver(
      'Card',
      'intro',
      $builder->fromPath("entity:node", "field_intro.value")
    );

    $registry->addFieldResolver(
      'Card',
      'link',
      $builder->fromPath("entity:node", "field_link")
    );

    $registry->addFieldResolver(
      'Card',
      'text',
      $builder->fromPath("entity:node", "field_text.value")
    );

    $registry->addFieldResolver(
      'Card',
      'title',
      $builder->fromPath("entity:node", "field_title.value")
    );

    
  }



  protected function addCardImageGroupParagraphFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'CardImageGroup',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'CardImageGroup',
      'cards',
      $builder->produce('entity_reference', [
        'entity' => $builder->fromParent(),
        'field' => $builder->fromValue('field_cards'),
      ])
    );

    $registry->addFieldResolver(
      'CardImageGroup',
      'columns',
      $builder->fromPath("entity:node", "field_columns.value")
    );

    $registry->addFieldResolver(
      'CardImageGroup',
      'title',
      $builder->fromPath("entity:node", "field_title.value")
    );
  }


  protected function addCtaParagraphFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'CTA',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );
    $registry->addFieldResolver(
      'CTA',
      'isDark',
      $builder->fromPath("entity:node", "field_dark.value")
    );
    $registry->addFieldResolver(
      'CTA',
      'intro',
      $builder->fromPath("entity:node", "field_intro.value")
    );
    $registry->addFieldResolver(
      'CTA',
      'title',
      $builder->fromPath("entity:node", "field_title.value")
    );
    $registry->addFieldResolver(
      'CTA',
      'url',
      $builder->fromPath("entity:node", "field_link")
    );
  }


  protected function addCardGroupParagraphFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'CardGroup',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );
    $registry->addFieldResolver(
      'CardGroup',
      'cards',
      $builder->produce('entity_reference_revisions', [
        'entity' => $builder->fromParent(),
        'field' => $builder->fromValue('field_cards'),
      ])
    );
    $registry->addFieldResolver(
      'CardGroup',
      'centered',
      $builder->fromPath("entity:node", "field_centered.value")
    );
    $registry->addFieldResolver(
      'CardGroup',
      'columns',
      $builder->fromPath("entity:node", "field_columns.value")
    );
    $registry->addFieldResolver(
      'CardGroup',
      'cta',
      $builder->fromPath("entity:node", "field_cta.value")
    );
    $registry->addFieldResolver(
      'CardGroup',
      'title',
      $builder->fromPath("entity:node", "field_title.value")
    );
  }


  protected function addCardImageParagraphFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'CardImage',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );
    
    $registry->addFieldResolver('CardImage', 'image',
      $builder->compose(
        $builder->produce('property_path')
          ->map('type', $builder->fromValue('entity:node'))
          ->map('value', $builder->fromParent())
          ->map('path', $builder->fromValue('field_image.target_id')),
        $builder->produce('entity_load')
          ->map('type', $builder->fromValue('file'))
          ->map('id', $builder->fromParent()),
        $builder->produce('image_derivative')
          ->map('entity', $builder->fromParent())
          ->map('style', $builder->fromValue('large')),
      )
    );
  }



  /**
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addQueryFields(ResolverRegistry $registry, ResolverBuilder $builder)
  {
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
      'pages',
      $builder->produce('query_base')
        ->map('offset', $builder->fromArgument('offset'))
        ->map('type', $builder->fromValue('page'))
        ->map('limit', $builder->fromArgument('limit'))
    );

    $registry->addFieldResolver(
      'Query',
      'articles',
      $builder->produce('query_base')
        ->map('offset', $builder->fromArgument('offset'))
        ->map('type', $builder->fromValue('article'))
        ->map('limit', $builder->fromArgument('limit'))
    );

    $registry->addTypeResolver('Paragraph', function ($value) {
      if ($value instanceof Paragraph) {
        switch ($value->bundle()) {
          case 'text':
            return 'Text';
          case 'hero_text':
            return 'HeroText';
          case 'static':
            return 'Static';
          case 'code_snippet':
            return 'CodeSnippet';
          case 'card':
            return 'Card';
          case 'card_image_group':
            return 'CardImageGroup';
          case 'cta':
            return 'CTA';
          case 'card_group':
            return 'CardGroup';
          case 'card_image':
            return 'CardImage';
        }
      }
      throw new Error('Could not resolve type ' . $value->bundle());
    });
  }

  /**
   * @param string $type
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addConnectionFields($type, ResolverRegistry $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      $type,
      'total',
      $builder->callback(function (QueryConnection $connection) {
        return $connection->total();
      })
    );

    $registry->addFieldResolver(
      $type,
      'items',
      $builder->callback(function (QueryConnection $connection) {
        return $connection->items();
      })
    );
  }
}
