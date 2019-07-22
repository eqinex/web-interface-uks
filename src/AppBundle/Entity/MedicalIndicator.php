<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MedicalIndicator
 *
 * @ORM\Table(name="medical_indicators")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MedicalIndicatorRepository")
 */
class MedicalIndicator
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Experiment")
     * @ORM\JoinColumn(name="experiment", referencedColumnName="id")
     */
    private $experiment;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Indicator")
     * @ORM\JoinColumn(name="indicator", referencedColumnName="id")
     */
    private $indicator;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

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
    public function getExperiment()
    {
        return $this->experiment;
    }

    /**
     * @param mixed $experiment
     * @return MedicalIndicator
     */
    public function setExperiment($experiment)
    {
        $this->experiment = $experiment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndicator()
    {
        return $this->indicator;
    }

    /**
     * @param mixed $indicator
     * @return MedicalIndicator
     */
    public function setIndicator($indicator)
    {
        $this->indicator = $indicator;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return MedicalIndicator
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}