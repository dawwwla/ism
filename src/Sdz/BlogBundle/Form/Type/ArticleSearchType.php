<?php
// src/Sdz/BlogBundle/Form/Type/ArticleSearchType.php

namespace Sdz\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleSearchType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('date',        'datetime')
      ->add('titre',       'text')
      ->add('contenu',     'textarea')
      ->add('image',       'text', array('required' => false))
      ->add('categories',  'entity',        array(
        'class'    => 'SdzBlogBundle:Categorie',
        'property' => 'nom',
        'multiple' => true
      ))
      /*
       * Rappel pour un champ de type collection :
       ** - 1er argument : nom du champ, ici "categories" car c'est le nom de l'attribut ;
       ** - 2e argument : type du champ, ici "collection" qui est une liste de quelque chose ;
       ** - 3e argument : tableau d'options du champ.
      */
      ->add('articleCompetences', 'collection', array(
          'type'         => new ArticleCompetenceType(),
          'allow_add'    => true,
          'allow_delete' => true,
          'by_reference' => false,
          'required'     => false
      ))
    ;

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Sdz\BlogBundle\Entity\Article'
    ));
  }

  public function getName()
  {
    return 'sdz_blogbundle_articlesearchtype';
  }
}
