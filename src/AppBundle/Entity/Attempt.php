<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attempts
 *
 * @ORM\Table(name="attempts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AttemptsRepository")
 */
class Attempt
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
     * @var \DateTime
     *
     * @ORM\Column(name="start_at", type="datetime")
     */
    private $startAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_at", type="datetime")
     */
    private $endAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="success", type="boolean", nullable=true)
     */
    private $success;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="log_transform", type="text", nullable=true)
     */
    private $logTransform;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=true)
     */
    private $deleted;

    public function __construct()
    {
        $this->startAt = new \DateTimeZone('UTC');
        $this->endAt = new \DateTimeZone('UTC');
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
    public function getExperiment()
    {
        return $this->experiment;
    }

    /**
     * @param mixed $experiment
     * @return Attempt
     */
    public function setExperiment($experiment)
    {
        $this->experiment = $experiment;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param \DateTime $startAt
     * @return Attempt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param \DateTime $endAt
     * @return Attempt
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param boolean $success
     * @return Attempt
     */
    public function setSuccess($success)
    {
        $this->success = $success;
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
     * @return Attempt
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogTransform()
    {
        return $this->logTransform;
    }

    /**
     * @param string $logTransform
     * @return Attempt
     */
    public function setLogTransform($logTransform)
    {
        $this->logTransform = $logTransform;
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

    /**
     * @return int
     */
    public function getTimeInterval()
    {
        $startAt = $this->startAt;
        $endAt = $this->endAt;
        $interval = $startAt->diff($endAt);

        return $interval->format('%H:%I:%S');
    }

    /**
     * @param $firstParameter
     * @param $secondParameter
     * @return int
     */
    public function getSumTime($firstParameter, $secondParameter)
    {
        $sum = strtotime("$firstParameter") + strtotime("$secondParameter") - strtotime("00:00:00");

        return date('H:i:s', $sum);
    }
}