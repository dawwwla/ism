<?php

namespace Ism\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LinksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text', array(
            'label' => 'Prénom'))
            ->add('lastname', 'text', array(
            'label' => 'Nom'))
            ->add('website', 'url', array(
            'label' => 'Site web',
            'trim'  => true))
            ->add('description', 'textarea', array(
            'attr'     => array('class' => 'span6', 'rows' => 12),
            'required' => false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ism\SiteBundle\Entity\Links'
        ));
    }

    public function getName()
    {
        return 'ism_sitebundle_linkstype';
    }
}
