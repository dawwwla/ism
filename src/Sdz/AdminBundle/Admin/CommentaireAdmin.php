<?php

namespace Sdz\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CommentaireAdmin extends Admin {
    // setup the default sort column and order
  protected $datagridValues = array(
                                    '_sort_order' => 'ASC',
                                    '_sort_by' => 'id'
                                    );

  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
    ->add('date')
    ->add('user')
    ->add('auteur')
    ->add('contenu')
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
    ->add('id')
    ->add('auteur')
    ->add('contenu')
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
    ->add('id')
    ->add('user')
    ->add('auteur')
    ->add('contenu')
    ->add('date')
    ;
  }
}
