<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="experiments_evaluation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExperimentEvaluationRepository")
 */
class ExperimentEvaluation
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
     * @var string
     *
     * @ORM\Column(name="key_column", type="string", length=255)
     */
    private $key = '';

    /**
     * @var string
     *
     * @ORM\Column(name="value_column", type="string", length=255)
     */
    private $value = '';

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
     * @return ExperimentEvaluation
     */
    public function setExperiment($experiment)
    {
        $this->experiment = $experiment;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return ExperimentEvaluation
     */
    public function setKey($key)
    {
        $this->key = $key;
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
     * @return ExperimentEvaluation
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}