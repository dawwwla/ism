<?php

namespace Ism\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RoadmapsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('produit', 'text', array(
                  'attr'  => array('class' => 'span3'),
                  'label' => 'Nom du produit'))
            ->add('version', 'number', array(
                  'attr'  => array('class' => 'span1')
                  ))
            ->add('datedeb', 'date', array(
                'label' => 'Date de début'))
            ->add('datefin', 'date', array(
                'label' => 'Date de fin'))
            ->add('status')
            ->add('description', 'textarea', array(
            'attr'     => array('class' => 'span6', 'rows' => 12),
            'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ism\SiteBundle\Entity\Roadmaps'
        ));
    }

    public function getName()
    {
        return 'ism_sitebundle_roadmapstype';
    }
}
