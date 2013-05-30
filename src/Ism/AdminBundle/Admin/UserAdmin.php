<?php

namespace Ism\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin {
    // setup the default sort column and order
  protected $datagridValues = array(
                              '_sort_order' => 'ASC',
                              '_sort_by' => 'id'
                              );

  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('username')
      ->add('email')
      ->add('plainPassword', 'text')
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('id')
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->add('username')
      ->add('email')
    ;
  }
}
