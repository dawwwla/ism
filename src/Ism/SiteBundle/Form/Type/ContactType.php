<?php
# src/Ism/SiteBundle/Form/Type/ContactType.php

namespace Ism\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
    ->add('email', 'email', array(
          'label' => 'Adresse email',
          'label_attr' => array('class' => 'control-label'),
          ))
    ->add('subject', 'text', array(
          'label' => 'Sujet',
          'label_attr' => array('class' => 'control-label')
          ))
    ->add('content', 'textarea', array(
          'label' => 'Message',
          'label_attr' => array('class' => 'control-label')
          ))
    ;
  }

  public function getName()
  {
    return 'ism_sitebundle_contacttype';
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Ism\SiteBundle\Form\Model\ContactModel'
    ));
  }

}

