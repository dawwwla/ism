<?php

namespace Ism\CommentBundle\Form;

use FOS\CommentBundle\Form\CommentType as BaseType;

class CommentType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', 'text')
                ->add('body', 'textarea');
    }
}
