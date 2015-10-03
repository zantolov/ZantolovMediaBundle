<?php

namespace Zantolov\MediaBundle\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Zantolov\AppBundle\Menu\MenuBuilderInterface;

class MenuBuilder implements MenuBuilderInterface
{

    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMenu(RequestStack $requestStack)
    {
        $menuItems = array();

        $menuItems['multimedia'] = $this->factory->createItem('Multimedia', array('label' => 'Files & Multimedia'))
            ->setAttribute('dropdown', true)
            ->setAttribute('icon', 'fa fa-image');

        $menuItems['multimedia']->addChild('Documents', array('route' => 'zantolov.media.document.index'))->setAttribute('icon', 'fa fa-file');
        $menuItems['multimedia']->addChild('Images', array('route' => 'media.image'))->setAttribute('icon', 'fa fa-image');
        $menuItems['multimedia']->addChild('Sliders', array('route' => 'zantolov.media.slider.index'))->setAttribute('icon', 'fa fa-folder');
        $menuItems['multimedia']->addChild('Slider items', array('route' => 'zantolov.media.slider-item.index'))->setAttribute('icon', 'fa fa-list');

        return $menuItems;
    }


    public function getOrder()
    {
        return 5;
    }
}