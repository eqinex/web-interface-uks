<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Experiment
 *
 * @ORM\Table(name="experiments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExperimentRepository")
 */
class Experiment
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Scene")
     * @ORM\JoinColumn(name="scene", referencedColumnName="id")
     */
    private $scene;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Operator")
     * @ORM\JoinColumn(name="operator", referencedColumnName="id")
     */
    private $operator;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime")
     */
    private $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_stop", type="datetime", nullable=true)
     */
    private $dateStop;

    /**
     * @var string
     *
     * @ORM\Column(name="log_voice", type="text", nullable=true)
     */
    private $logVoice;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=true)
     */
    private $deleted;

    public function __construct()
    {
        $this->dateStart = new \DateTimeZone('UTC');
        $this->dateStop = new \DateTimeZone('UTC');
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
     * Set scene
     *
     * @param Scene $scene
     *
     * @return $this
     */
    public function setScene($scene)
    {
        $this->scene = $scene;
        return $this;
    }

    /**
     * Get scene
     *
     * @return Scene
     */
    public function getScene()
    {
        return $this->scene;
    }

    /**
     * Set operator
     *
     * @param Operator $operator
     *
     * @return $this
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Get operator
     *
     * @return Operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param \DateTime $dateStart
     * @return Experiment
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateStop()
    {
        return $this->dateStop;
    }

    /**
     * @param \DateTime $dateStop
     * @return Experiment
     */
    public function setDateStop($dateStop)
    {
        $this->dateStop = $dateStop;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogVoice()
    {
        return $this->logVoice;
    }

    /**
     * @param string $logVoice
     * @return Experiment
     */
    public function setLogVoice($logVoice)
    {
        $this->logVoice = $logVoice;
        return $this;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return $this
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}