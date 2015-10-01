<?php

namespace Zantolov\MediaBundle\EventListener;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\HttpFoundation\File\File;
use Zantolov\MediaBundle\Entity\Image;

class UploadListener
{
    /** @var  Registry */
    private $doctrine;


    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function onUpload(PostPersistEvent $event)
    {
        /** @var File $file */
        $file = $event->getFile();

        try {

            $type = $file->getMimeType();
            $imageMimeTypes = array(
                'image/bmp', 'image/jpg', 'image/jpeg', 'image/gif', 'image/png'
            );

            if (in_array($type, $imageMimeTypes)) {
                //move image
                $file->move(dirname($file->getPath()) . '/images/default');

                $image = new Image();
                $image->setImageFile($file);
                $image->setImageName($file->getFilename());
                $this->doctrine->getManager()->persist($image);
                $this->doctrine->getManager()->flush();
            }

        } catch (\Exception $e) {
//            unlink($file->getPathname());
        }

    }
}

