<?php

namespace Zantolov\MediaBundle\Service;


use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class ZantolovFileNamer implements NamerInterface
{
    use NamerTrait;

    /**
     * {@inheritDoc}
     */
    public function name($object, PropertyMapping $mapping)
    {
        /** @var UploadedFile $file */
        $file = $mapping->getFile($object);

        $oldName = $file->getClientOriginalName();
        $name = $this->getFileName($oldName);

        if ($extension = $this->getExtension($file)) {
            $name = sprintf('%s.%s', $name, $extension);
        }

        return $name;
    }

}