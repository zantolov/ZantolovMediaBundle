<?php

namespace Zantolov\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SliderItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttribute('autocomplete', 'off')
            ->add('name')
            ->add('link', null, array('required' => false))
            ->add('body', 'ckeditor', array('required' => false))
            ->add('active', null, array('required' => false))
            ->add('slider', null, array('attr' => array('autocomplete' => 'off')))
            ->add('image');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Zantolov\MediaBundle\Entity\SliderItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'zantolov_mediabundle_slideritem';
    }
}
