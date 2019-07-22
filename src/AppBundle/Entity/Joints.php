<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Joints
 *
 * @ORM\Table(name="joints")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JointsRepository")
 */
class Joints
{
    const PART_L_ELBOW_R = 'L.ElbowR';
    const PART_L_WRIST_R = 'L.WristR';
    const PART_R_ELBOW_R = 'R.ElbowR';
    const PART_R_WRIST_R = 'R.WristR';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=128, nullable=true)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="gain_gravity", type="float", options={"default":0})
     */
    private $gainGravity;

    /**
     * @var float
     *
     * @ORM\Column(name="gain_friction", type="float", options={"default":0})
     */
    private $gainFriction;

    /**
     * @var float
     *
     * @ORM\Column(name="delta_length", type="float", nullable=true)
     */
    private $deltaLength;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Joints
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Joints
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return float
     */
    public function getGainGravity()
    {
        return $this->gainGravity;
    }

    /**
     * @param float $gainGravity
     * @return Joints
     */
    public function setGainGravity($gainGravity)
    {
        $this->gainGravity = $gainGravity;
        return $this;
    }

    /**
     * @return float
     */
    public function getGainFriction()
    {
        return $this->gainFriction;
    }

    /**
     * @param float $gainFriction
     * @return Joints
     */
    public function setGainFriction($gainFriction)
    {
        $this->gainFriction = $gainFriction;
        return $this;
    }

    /**
     * @return float
     */
    public function getDeltaLength()
    {
        return $this->deltaLength;
    }

    /**
     * @param float $deltaLength
     * @return Joints
     */
    public function setDeltaLength($deltaLength)
    {
        $this->deltaLength = $deltaLength;
        return $this;
    }

    /**
     * @param Joints $joints
     * @return bool
     */
    public function checkGrants(Joints $joints)
    {
        return $joints->getName() == Joints::PART_L_ELBOW_R ||
            $joints->getName() == Joints::PART_L_WRIST_R ||
            $joints->getName() == Joints::PART_R_ELBOW_R ||
            $joints->getName() == Joints::PART_R_WRIST_R
        ;
    }
}