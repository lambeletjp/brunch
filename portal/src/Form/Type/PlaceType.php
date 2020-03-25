<?php
namespace App\Form\Type;

use App\Entity\Place;
use App\Entity\Image;
use DateTime;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
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
            ->add('weekStart', TimeType::class, array('widget'=> 'single_text', 'required' => false))
            ->add('weekStop', TimeType::class, array('widget'=> 'single_text', 'required' => false))
            ->add('saturdayStart', TimeType::class, array('widget'=> 'single_text', 'required' => false))
            ->add('saturdayStop', TimeType::class, array('widget'=> 'single_text', 'required' => false))
            ->add('sundayStart', TimeType::class, array('widget'=> 'single_text', 'required' => false))
            ->add('sundayStop', TimeType::class, array('widget'=> 'single_text', 'required' => false))
            ->add('images', CollectionType::class, array(
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'placeAdd',
            ])
            ->add('save', SubmitType::class, array(
                'attr' => array('class' => 'save btn btn-primary'),
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Place::class,
        ));
    }
}
