<?php

namespace Zantolov\MediaBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ImageableTrait
{

    /**
     * @var \Zantolov\MediaBundle\Entity\Image
     * @ORM\ManyToOne(targetEntity="Zantolov\MediaBundle\Entity\Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="SET NULL")
     **/
    private $image;

    /**
     * @return \Zantolov\MediaBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param \Zantolov\MediaBundle\Entity\Image $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

}
