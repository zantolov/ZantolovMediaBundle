<?php

namespace Zantolov\MediaBundle\Service;

use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ZantolovOneupFileNamer implements NamerInterface
{
    use NamerTrait;

    public function name(FileInterface $file)
    {
        if ($file instanceof UploadedFile) {

            $oldName = $file->getClientOriginalName();
            $name = $this->getFileName($oldName);

            if ($extension = $this->getExtension($file)) {
                $name = sprintf('%s.%s', $name, $extension);
            }
            return $name;

        } else {
            $oldName = $file->getBasename();
            $ext = $file->getExtension();
            $name = sprintf('%s.%s', $this->getFileName($oldName), $ext);
            return $name;
        }
    }
}
