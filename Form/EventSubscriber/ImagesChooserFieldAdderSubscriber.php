<?php

namespace Zantolov\MediaBundle\Form\EventSubscriber;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Zantolov\MediaBundle\Form\Type\ImageChooserType;

class ImagesChooserFieldAdderSubscriber implements EventSubscriberInterface
{

    private $propertyPath;

    public function __construct($path)
    {
        $this->setPropertyPath($path);
    }


    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }

    /**
     * @return mixed
     */
    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    /**
     * @param mixed $propertyPath
     */
    public function setPropertyPath($propertyPath)
    {
        $this->propertyPath = $propertyPath;
    }

    public function addFormField(FormInterface $form, $images)
    {
        $formOptions = $this->getFormTypeDefaults();

        if (is_array($images)) {
            $selectedIds = is_array($images) ? array_values($images) : null;
            $formOptions['query_builder'] = function (EntityRepository $repository) use ($selectedIds) {
                $qb = $repository->createQueryBuilder('I')->where('I.id IN(:ids)')->setParameter('ids', $selectedIds);
                return $qb;
            };

        } elseif ($images instanceof Collection) {
            $formOptions['choices'] = $images;
        }

        $form->add($this->getPropertyPath(), new ImageChooserType(), $formOptions);
    }

    private function getFormTypeDefaults()
    {
        return array(
            'class'    => 'ZantolovMediaBundle:Image',
            'label'    => 'Images',
            'multiple' => true,
            'required' => false,
            'attr'     => array('class' => 'noSelect2'),
        );
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $images = $accessor->getValue($data, $this->getPropertyPath());

        $this->addFormField($form, $images);

    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $accessor = PropertyAccess::createPropertyAccessor();
        $images = $accessor->getValue($data, (is_array($data) ? '[images]' : 'images'));

        $this->addFormField($form, $images);

    }

}