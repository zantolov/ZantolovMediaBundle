<?php

namespace Zantolov\MediaBundle\DataFixtures\ORM;

use Zantolov\AppBundle\DataFixtures\ORM\AbstractDbFixture;
use Zantolov\MediaBundle\Entity\Image;

class LoadImagesData extends AbstractDbFixture
{

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 20; $i++) {

            $img = new Image();
            $img->setActive(true);
            $img->setImageName($i . '.jpg');

            $url = 'http://lorempixel.com/500/500/';
            $imgFile = realpath(__DIR__ . '/../../../../../../web/uploads/images/default/') . '/' . $i . '.jpg';

            file_put_contents($imgFile, file_get_contents($url));

            $this->setReference('image' . $i, $img);

            $manager->persist($img);
        }

        $manager->flush();
    }

}
