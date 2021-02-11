<?php

namespace Drupal\graphql_examples\Plugin\GraphQL\SchemaExtension;

use Drupal\Core\Plugin\Context\ContextDefinition;
use Drupal\graphql\GraphQL\ResolverBuilder;
// use Drupal\graphql\GraphQL\ResolverRegistry;
use Drupal\graphql\GraphQL\ResolverRegistryInterface;
// use Drupal\graphql\Plugin\GraphQL\Schema\SdlSchemaPluginBase;
use Drupal\graphql\Plugin\GraphQL\SchemaExtension\SdlSchemaExtensionPluginBase;
use Drupal\graphql_examples\Wrappers\QueryConnection;
use GraphQL\Error\Error;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * @SchemaExtension(
 *   id = "Paragraph",
 *   name = "Paragraph extension",
 *   description = "A simple extension that adds node related fields.",
 *   schema = "Base"
 * )
 */
class ParagraphResolvers extends SdlSchemaExtensionPluginBase
{

  /**
   * {@inheritdoc}
   */

  public function registerResolvers(ResolverRegistryInterface $registry) {
    $builder = new ResolverBuilder();

    $this->addQueryFields($registry, $builder);
    $this->addTextParagraphFields($registry, $builder);
    $this->addHeroTextParagraphFields($registry, $builder);
    $this->addStaticParagraphFields($registry, $builder);
    $this->addCodeSnippetParagraphFields($registry, $builder);
    $this->addCardParagraphFields($registry, $builder);
    $this->addCardImageGroupParagraphFields($registry, $builder);
    $this->addCtaParagraphFields($registry, $builder);
    $this->addCardGroupParagraphFields($registry, $builder);
    $this->addCardImageParagraphFields($registry, $builder);
    $this->addHeroCtaParagraphFields($registry, $builder);
  }


 /**
   * @param \Drupal\graphql\GraphQL\ResolverRegistryInterface $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
*/

  protected function addTextParagraphFields(ResolverRegistryInterface $registry, ResolverBuilder $builder)
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
      $builder->produce('field')
        ->map('entity', $builder->fromParent())
        ->map('field', $builder->fromValue('field_text'))
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


  protected function addHeroTextParagraphFields(ResolverRegistryInterface $registry, ResolverBuilder $builder)
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
      $builder->produce('field')
        ->map('entity', $builder->fromParent())
        ->map('field', $builder->fromValue('field_text'))
    );

    $registry->addFieldResolver(
      'HeroText',
      'title',
      $builder->fromPath("entity:node", "field_title.value")
    );
  }


  protected function addStaticParagraphFields(ResolverRegistryInterface $registry, ResolverBuilder $builder)
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


  protected function addCodeSnippetParagraphFields(ResolverRegistryInterface $registry, ResolverBuilder $builder)
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
      $builder->produce('field')
        ->map('entity', $builder->fromParent())
        ->map('field', $builder->fromValue('field_text'))
    );

    $registry->addFieldResolver(
      'CodeSnippet',
      'theme',
      $builder->fromPath("entity:node", "field_theme.value")
    );
  }


  protected function addCardParagraphFields(ResolverRegistryInterface $registry, ResolverBuilder $builder)
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
      $builder->produce('field')
        ->map('entity', $builder->fromParent())
        ->map('field', $builder->fromValue('field_text'))
    );

    $registry->addFieldResolver(
      'Card',
      'title',
      $builder->fromPath("entity:node", "field_title.value")
    );

    
  }



  protected function addCardImageGroupParagraphFields(ResolverRegistryInterface $registry, ResolverBuilder $builder)
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


  protected function addCtaParagraphFields(ResolverRegistryInterface $registry, ResolverBuilder $builder)
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
    $registry->addFieldResolver(
      'CTA',
      'text',
      $builder->produce('field')
        ->map('entity', $builder->fromParent())
        ->map('field', $builder->fromValue('field_text'))
    );
  }


  protected function addCardGroupParagraphFields(ResolverRegistryInterface $registry, ResolverBuilder $builder)
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


  protected function addCardImageParagraphFields(ResolverRegistryInterface $registry, ResolverBuilder $builder)
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




  protected function addHeroCtaParagraphFields(ResolverRegistryInterface $registry, ResolverBuilder $builder)
  {
    $registry->addFieldResolver(
      'HeroCta',
      'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver(
      'HeroCta',
      'title',
      $builder->fromPath("entity:node", "field_title.value")
    );

    $registry->addFieldResolver(
      'HeroCta',
      'intro',
      $builder->fromPath("entity:node", "field_intro.value")
    );

    $registry->addFieldResolver(
      'HeroCta',
      'text',
      $builder->produce('field')
        ->map('entity', $builder->fromParent())
        ->map('field', $builder->fromValue('field_text'))
    );


    $registry->addFieldResolver('HeroCta', 'image',
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
      'HeroCta',
      'link',
      $builder->fromPath("entity:node", "field_link")
    );



  }


  /**
   * @param \Drupal\graphql\GraphQL\ResolverRegistryInterface $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */

  protected function addQueryFields(ResolverRegistryInterface $registry, ResolverBuilder $builder)
  {
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
          case 'hero_cta':
            return 'HeroCta';
        }
      }
      throw new Error('Could not resolve type ' . $value->bundle());
    });
  }

}
