<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Simulator
 *
 * @ORM\Table(name="content")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContentRepository")
 */
class Content
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
     * @ORM\ManyToOne(targetEntity="Dlc")
     * @ORM\JoinColumn(name="dlc", referencedColumnName="id")
     */
    private $dlc;

    /**
     * @var string
     *
     * @ORM\Column(name="transform", type="text", nullable=true)
     */
    private $transform;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Content
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return Content
     */
    public function setScene($scene)
    {
        $this->scene = $scene;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDlc()
    {
        return $this->dlc;
    }

    /**
     * @param mixed $dlc
     * @return Content
     */
    public function setDlc($dlc)
    {
        $this->dlc = $dlc;
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
     * @return Content
     */
    public function setTransform($transform)
    {
        $this->transform = $transform;
        return $this;
    }

}

