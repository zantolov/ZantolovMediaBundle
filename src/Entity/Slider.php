<?php

namespace Zantolov\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Zantolov\AppBundle\Entity\Traits\ActivableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="sliders")
 * @ORM\HasLifecycleCallbacks
 */
class Slider
{
    use TimestampableEntity;
    use ActivableTrait;

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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="SliderItem", mappedBy="slider", cascade={"persist"})
     **/
    private $items;


    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

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
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param SliderItem $item
     */
    public function addItem(SliderItem $item)
    {
        $this->getItems()->add($item);
        $item->setSlider($this);
    }

    /**
     * @param SliderItem $item
     */
    public function removeItem(SliderItem $item)
    {
        $this->getItems()->removeElement($item);
        $item->setSlider();
    }

    public function __toString()
    {
        return $this->getName();
    }

}