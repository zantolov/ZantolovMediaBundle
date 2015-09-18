<?php

namespace Zantolov\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ImageChooserType extends AbstractType
{

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['image_browse_route_name'] = 'media.image.popup.browse';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'class'    => 'ZantolovMediaBundle:Image',
            'label'    => 'Images',
            'multiple' => true,
            'required' => false,
            'attr'     => array('class' => 'noSelect2'),
        ));
    }

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'image_chooser';
    }

}