<?php
// src/Sdz/UserBundle/Form/MembreType.php

namespace Sdz\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MembreType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('lastname',     'text',     array(
            'label'  => 'Nom'
            ))
      ->add('firstname',    'text',     array(
            'label'  => 'Prénom'
            ))
      ->add('phone',        'text',     array(
            'label'  => 'Numéro de téléphone',
            ))
      ->add('website',      'text',     array(
            'label'  => 'Site web',
            ))
      ->add('jobTitle',     'text',     array(
            'label'  => 'Titre métier'
            ))
      ->add('jobDescription', 'textarea', array(
            'label'  => 'Description métier',
            'attr' => array('class' => 'span6', 'rows' => '12'),
            ))
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Sdz\UserBundle\Entity\Membre'
    ));
  }

  public function getName()
  {
    return 'sdz_userbundle_membretype';
  }
}
