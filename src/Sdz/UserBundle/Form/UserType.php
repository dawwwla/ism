<?php
// src/Sdz/UserBundle/Form/UserType.php

namespace Sdz\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('dateOfBirth',  'birthday', array('label'  => 'Date de naissance'))
      ->add('firstname',    'text',     array('label'  => 'Prénom'))
      ->add('lastname',     'text',     array('label'  => 'Nom'))
      ->add('website',      'text',     array('label'  => 'Site web'))
      ->add('gender',       'text',     array('label'  => 'Sexe'))
      ->add('phone',        'text',     array('label'  => 'Numéro de téléphone'))
      ->add('role',         'text',     array('label'  => 'Rôle'))
      ->add('description',  'textarea', array('label'  => 'Description'))
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
    return 'sdz_userbundle_usertype';
  }
}
