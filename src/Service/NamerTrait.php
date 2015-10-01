<?php

namespace Zantolov\MediaBundle\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait NamerTrait
{

    public function getFileName($oldName)
    {
        $name = $oldName;
        $name = Urlizer::urlize($name);
        $name .= '-' . time();

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
