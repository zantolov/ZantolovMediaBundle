<?php

namespace Zantolov\MediaBundle\Service;

use Symfony\Component\Routing\Router;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ZantolovVichUploaderHelper
{

    /** @var UploaderHelper */
    private $vichHelper;

    /**
     * @var string
     */
    private $uploadDir;

    /**
     * @var Router
     */
    private $router;


    /**
     * @param UploaderHelper $helper
     * @param Router $router
     * @param string $uploadDir
     */
    public function __construct(UploaderHelper $helper, Router $router, $uploadDir = '')
    {
        $this->router = $router;
        $this->uploadDir = $uploadDir;
        $this->vichHelper = $helper;
    }


    /**
     * @param $obj
     * @param $fieldName
     * @param null $className
     * @param bool $absolute
     * @return string
     */
    public function getAssetUrl($obj, $fieldName, $className = null, $absolute = true)
    {
        $absoluteHomeUrl = $this->router->generate('homepage', array(), true);
        $url = $this->uploadDir . $this->vichHelper->asset($obj, $fieldName, $className);

        if ($absolute) {
            return $absoluteHomeUrl . $url;
        }

        return $url;
    }

}