<?php
// src/Ism/BlogBundle/Form/Type/ImageType.php

namespace Ism\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('file', 'file')
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Ism\BlogBundle\Entity\Image'
    ));
  }

  public function getName()
  {
    return 'ismblogbundle_imagetype';
  }
}
