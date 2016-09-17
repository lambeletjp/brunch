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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ImageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('imageName', 'text');
        $formMapper->add('approved', CheckboxType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('imageName');
        $datagridMapper->add('approved');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('imageName');
        $listMapper->addIdentifier('approved');
    }
}