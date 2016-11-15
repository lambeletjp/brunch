<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\Image;
use DateTime;
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
            ->add('sundayStop', TimeType::class, array('required' => false, 'data' => new DateTime('00:00:00')))
            ->add('images', CollectionType::class, array(
                'entry_type' => ImageType::class,
                'allow_add' => true,
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