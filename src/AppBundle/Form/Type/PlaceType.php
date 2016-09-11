<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\Image;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
                ->add('address', TextType::class)
                ->add('postalCode', NumberType::class)
                ->add('city', TextType::class)
                ->add('price', MoneyType::class)
                ->add('hasBuffet', CheckboxType::class,array(
                    'required' => false
                ))
                ->add('mondayStart', TimeType::class,array(                     'required' => false                 ))
                ->add('mondayStop', TimeType::class,array(                     'required' => false                 ))
                ->add('tuesdayStart', TimeType::class,array(                     'required' => false                 ))
                ->add('tuesdayStop', TimeType::class,array(                     'required' => false                 ))
                ->add('wednesdayStart', TimeType::class,array(                     'required' => false                 ))
                ->add('wednesdayStop', TimeType::class,array(                     'required' => false                 ))
                ->add('thursdayStart', TimeType::class,array(                     'required' => false                 ))
                ->add('thursdayStop', TimeType::class,array(                     'required' => false                 ))
                ->add('fridayStart', TimeType::class,array(                     'required' => false                 ))
                ->add('fridayStop', TimeType::class,array(                     'required' => false                 ))
                ->add('saturdayStart', TimeType::class,array(                     'required' => false                 ))
                ->add('saturdayStop', TimeType::class,array(                     'required' => false                 ))
                ->add('sundayStart', TimeType::class,array(                     'required' => false                 ))
                ->add('sundayStop', TimeType::class,array(                     'required' => false                 ))
                ->add('images', CollectionType::class, array(
                        'entry_type' => ImageType::class,
                        'allow_add'    => true,
                        'by_reference' => false,
                        'allow_delete' => true,
                ))
                ->add('save', SubmitType::class, array(
                    'attr' => array('class' => 'save'),
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Place',
        ));
    }
}