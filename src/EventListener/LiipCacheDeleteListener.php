<?php

namespace Zantolov\MediaBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Zantolov\MediaBundle\Entity\Interfaces\LiipCacheableInterface;
use Zantolov\MediaBundle\Service\ZantolovVichUploaderHelper;

class LiipCacheDeleteListener
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var ZantolovVichUploaderHelper
     */
    private $uploaderHelper;

    public function __construct(CacheManager $cm, ZantolovVichUploaderHelper $helper)
    {
        $this->cacheManager = $cm;
        $this->uploaderHelper = $helper;
    }

    public function deleteCache(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if ($entity instanceof LiipCacheableInterface) {
            $path = $this->uploaderHelper->getAssetUrl($entity, 'imageFile', null, false);
            $this->cacheManager->remove($path);
        }
    }


    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $this->deleteCache($eventArgs);
    }


    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->deleteCache($eventArgs);
    }


    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $this->deleteCache($eventArgs);
    }

}