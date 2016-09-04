<?php
/**
 * Created by PhpStorm.
 * User: lambeletjp
 * Date: 04/09/16
 * Time: 11:13
 */

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PlaceImageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('imageName', 'text');
        $formMapper->add('placeId', 'integer');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('imageName');
        $datagridMapper->add('placeId');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('imageName');
        $listMapper->addIdentifier('placeId');
    }
}