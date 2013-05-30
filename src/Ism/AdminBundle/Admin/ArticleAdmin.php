<?php

namespace Ism\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleAdmin extends Admin {
    // setup the default sort column and order
  protected $datagridValues = array(
                                    '_sort_order' => 'ASC',
                                    '_sort_by' => 'id'
                                    );

  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
    ->add('titre')
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
    ->add('titre')
    ->add('slug')
    ->add('auteur')
    ->add('contenu')
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
    ->add('id')
    ->add('titre')
    ->add('slug')
    ->add('date')
    ->add('user')
    ->add('auteur')
    ->add('contenu')
    ->add('articleCompetences', null, array('label' => 'Competences'))
    ->add('image', null, array('label' => 'Image'))
    ->add('commentaires', null, array('label' => 'Commentaires'))
    ;
  }
}
