<?php

namespace Zantolov\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Zantolov\AppBundle\Entity\Traits\ActivableTrait;
use Zantolov\AppBundle\Entity\Traits\SortableTrait;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 * @ORM\Table(name="slider_items")
 * @ORM\HasLifecycleCallbacks
 */
class SliderItem
{
    use TimestampableEntity;
    use ActivableTrait;
    use SortableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;


    /**
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Slider", inversedBy="items")
     * @ORM\JoinColumn(name="slider_id", referencedColumnName="id")
     **/
    private $slider;



    /**
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     **/
    private $image;


    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * @param mixed $slider
     */
    public function setSlider(Slider $slider = null)
    {
        $this->slider = $slider;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

}
