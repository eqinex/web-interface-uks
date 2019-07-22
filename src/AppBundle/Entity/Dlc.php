<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Simulator
 *
 * @ORM\Table(name="dlc")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DlcRepository")
 */
class Dlc
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="DlcType")
     * @ORM\JoinColumn(name="type", referencedColumnName="id")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="bundle", type="string", length=255)
     */
    private $bundle = "";

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255)
     */
    private $hash = "";

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="text", nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255)
     */
    private $version = "";

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Dlc", mappedBy="items")
     */
    private $environments;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Dlc", inversedBy="environments")
     * @ORM\JoinTable(name="environment_items",
     *     joinColumns={@ORM\JoinColumn(name="environment_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="item_id", referencedColumnName="id")})
     */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EnvironmentOde")
     * @ORM\JoinColumn(name="environments_ode", referencedColumnName="id", nullable=true)
     */
    private $environmentsOde;

    public function __construct()
    {
        $this->environments = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Dlc
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Dlc
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @param string $bundle
     * @return Dlc
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return Dlc
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Dlc
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return Dlc
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     * @return Dlc
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnvironmentsOde()
    {
        return $this->environmentsOde;
    }

    /**
     * @param mixed $environmentsOde
     * @return Dlc
     */
    public function setEnvironmentsOde($environmentsOde)
    {
        $this->environmentsOde = $environmentsOde;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return ArrayCollection
     */
    public function getEnvironments()
    {
        return $this->environments;
    }

    /**
     * @param Dlc $item
     * @return Dlc
     */
    public function addItem(Dlc $item)
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->environments[] = $this;
        }
        return $this;
    }

    /**
     * @param Dlc $item
     */
    public function removeItem(Dlc $item)
    {
        $this->items->removeElement($item);
        $item->environments->removeElement($this);
    }

    public function getItemsIds()
    {
        $ids = [];

        foreach ($this->getItems() as $item) {
            $ids[] = $item->getId();
        }

        return $ids;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }
}