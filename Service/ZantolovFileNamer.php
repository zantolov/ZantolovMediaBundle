<?php

namespace Zantolov\MediaBundle\Service;


use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class ZantolovFileNamer implements NamerInterface
{

    /**
     * {@inheritDoc}
     */
    public function name($object, PropertyMapping $mapping)
    {
        /** @var UploadedFile $file */
        $file = $mapping->getFile($object);

        $name = $file->getClientOriginalName();
        $name = Urlizer::urlize($name);
        $name .= '-' . time();

        if ($extension = $this->getExtension($file)) {
            $name = sprintf('%s.%s', $name, $extension);
        }

        return $name;
    }

    protected function getExtension(UploadedFile $file)
    {
        $originalName = $file->getClientOriginalName();

        if ($extension = pathinfo($originalName, PATHINFO_EXTENSION)) {
            return $extension;
        }

        if ($extension = $file->guessExtension()) {
            return $extension;
        }

        return null;
    }

}