<?php
# src/Sdz/SiteBundle/Form/ContactType.php

namespace Sdz\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('email', 'email',  array('label' => 'Adresse')
                ->add('subject', 'text')
                ->add('content', 'textarea');
    }

    public function getName()
    {
        return 'sdz_sitebundle_contacttype';
    }
}

