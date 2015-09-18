<?php

namespace Zantolov\MediaBundle\DataFixtures\ORM;

use Zantolov\AppBundle\DataFixtures\ORM\AbstractDbFixture;
use Zantolov\MediaBundle\Entity\Image;
use Zantolov\MediaBundle\Entity\Slider;
use Zantolov\MediaBundle\Entity\SliderItem;

class LoadSliderData extends AbstractDbFixture
{

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 3; $i++) {

            $slider = new Slider();
            $slider->setName('Slider ' . $i);
            $slider->setActive(true);

            for ($j = 0; $j < 3; $j++) {
                $si = new SliderItem();
                $si->setActive(1);
                $si->setName('Slider ' . $i . ' Item ' . $j);
                $si->setBody($faker->realText());
                $slider->addItem($si);
            }

            $manager->persist($slider);
        }

        $manager->flush();
    }

}
