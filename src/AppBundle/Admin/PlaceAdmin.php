<?php

namespace AppBundle\Admin;
/**
 * Created by PhpStorm.
 * User: lambeletjp
 * Date: 04/09/16
 * Time: 11:03
 */


use AppBundle\Form\ImageType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Entity\Image;
use DateTime;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PlaceAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', 'text');
        $formMapper->add('approved', CheckboxType::class);
        $formMapper->add('name', TextType::class)
        ->add('address', TextType::class)
        ->add('postalCode', NumberType::class)
        ->add('city', TextType::class)
        ->add('country', TextType::class)
        ->add('priceStart', MoneyType::class, array(
            'required' => false
        ))
        ->add('priceEnd', MoneyType::class, array(
            'required' => false
        ))
        ->add('hasBuffet', CheckboxType::class, array(
            'required' => false
        ))
        ->add('weekStart', TimeType::class, array('required' => false, 'data' => new DateTime('00:00:00')))
        ->add('weekStop', TimeType::class, array('required' => false, 'data' => new DateTime('00:00:00')))
        ->add('saturdayStart', TimeType::class, array('required' => false, 'data' => new DateTime('00:00:00')))
        ->add('saturdayStop', TimeType::class, array('required' => false, 'data' => new DateTime('00:00:00')))
        ->add('sundayStart', TimeType::class, array('required' => false, 'data' => new DateTime('00:00:00')))
        ->add('sundayStop', TimeType::class, array('required' => false, 'data' => new DateTime('00:00:00')));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
        $datagridMapper->add('approved');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name');
        $listMapper->addIdentifier('approved');
    }
}