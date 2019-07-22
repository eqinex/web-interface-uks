<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Simulator
 *
 * @ORM\Table(name="camera")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CameraRepository")
 */
class Camera
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
     * @ORM\ManyToOne(targetEntity="Scene")
     * @ORM\JoinColumn(name="scene", referencedColumnName="id")
     */
    private $scene;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="transform", type="text", nullable=true)
     */
    private $transform;

    /**
     * @var float
     *
     * @ORM\Column(name="fov", type="float")
     */
    private $fov;

    /**
     * @var int
     *
     * @ORM\Column(name="dlc", type="integer", nullable=true)
     */
    private $dlc;

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
    public function getScene()
    {
        return $this->scene;
    }

    /**
     * @param mixed $scene
     * @return Camera
     */
    public function setScene($scene)
    {
        $this->scene = $scene;
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
     * @return Object
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransform()
    {
        return $this->transform;
    }

    /**
     * @param string $transform
     * @return Camera
     */
    public function setTransform($transform)
    {
        $this->transform = $transform;
        return $this;
    }

    /**
     * @return float
     */
    public function getFov()
    {
        return $this->fov;
    }

    /**
     * @return integer
     */
    public function getDlc()
    {
        return $this->dlc;
    }

    /**
     * @param float $fov
     * @return Camera
     */
    public function setFov($fov)
    {
        $this->fov = $fov;
        return $this;
    }

    /**
     * @param int $dlc
     * @return Camera
     */
    public function setDlc($dlc)
    {
        $this->dlc = $dlc;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}

