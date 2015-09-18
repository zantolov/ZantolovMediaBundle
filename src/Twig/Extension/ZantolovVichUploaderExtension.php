<?php

namespace Zantolov\MediaBundle\Twig\Extension;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Zantolov\MediaBundle\Service\ZantolovVichUploaderHelper;

class ZantolovVichUploaderExtension extends \Twig_Extension
{

    /**
     * @var ZantolovVichUploaderHelper $helper
     */
    private $helper;

    /**
     * Constructs a new instance of ZantolovVichUploaderExtension.
     *
     * @param UploaderHelper $helper
     */
    public function __construct(ZantolovVichUploaderHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param $obj
     * @param $fieldName
     * @param null $className
     * @return string
     */
    public function getAssetPath($obj, $fieldName, $className = null, $absolute = true)
    {
        return $this->helper->getAssetUrl($obj, $fieldName, $className, $absolute);
    }

    public function getName()
    {
        return 'zantolov_vich_uploader';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'zantolov_uploader_asset' => new \Twig_Function_Method($this, 'getAssetPath')
        );
    }

}