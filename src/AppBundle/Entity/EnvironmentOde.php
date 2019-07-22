<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Indicator
 *
 * @ORM\Table(name="environment_ode")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EnvironmentOdeRepository")
 */
class EnvironmentOde
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Dlc")
     * @ORM\JoinColumn(name="environment", referencedColumnName="id")
     */
    protected $environment;

    /**
     * @var int
     *
     * @ORM\Column(name="core", type="integer")
     */
    protected $core;

    /**
     * @var integer
     *
     * @ORM\Column(name="sps", type="float")
     */
    protected $sps;

    /**
     * @var float
     *
     * @ORM\Column(name="step_time", type="float")
     */
    protected $stepTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="step_rate", type="integer")
     */
    protected $stepRate;

    /**
     * @var integer
     *
     * @ORM\Column(name="iterations", type="integer")
     */
    protected $iterations;

    /**
     * @var float
     *
     * @ORM\Column(name="erp", type="float")
     */
    protected $erp;

    /**
     * @var float
     *
     * @ORM\Column(name="cfm", type="float")
     */
    protected $cfm;

    /**
     * @var float
     *
     * @ORM\Column(name="gravity", type="float")
     */
    protected $gravity;

    /**
     * EnvironmentOde constructor.
     */
    public function __construct()
    {
        $this
            ->setCore(-1)
            ->setSps(500)
            ->setStepTime(0.002)
            ->setStepRate(1)
            ->setIterations(25)
            ->setErp(0.5)
            ->setCfm(0.00002)
            ->setGravity(-9.8)
        ;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param mixed $environment
     * @return EnvironmentOde
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @return int
     */
    public function getCore()
    {
        return $this->core;
    }

    /**
     * @param int $core
     * @return EnvironmentOde
     */
    public function setCore($core)
    {
        $this->core = $core;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSps()
    {
        return $this->sps;
    }

    /**
     * @param mixed $sps
     * @return EnvironmentOde
     */
    public function setSps($sps)
    {
        $this->sps = $sps;
        return $this;
    }

    /**
     * @return float
     */
    public function getStepTime()
    {
        return $this->stepTime;
    }

    /**
     * @param float $stepTime
     * @return EnvironmentOde
     */
    public function setStepTime($stepTime)
    {
        $this->stepTime = $stepTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getStepRate()
    {
        return $this->stepRate;
    }

    /**
     * @param int $stepRate
     * @return EnvironmentOde
     */
    public function setStepRate($stepRate)
    {
        $this->stepRate = $stepRate;
        return $this;
    }

    /**
     * @return int
     */
    public function getIterations()
    {
        return $this->iterations;
    }

    /**
     * @param int $iterations
     * @return EnvironmentOde
     */
    public function setIterations($iterations)
    {
        $this->iterations = $iterations;
        return $this;
    }

    /**
     * @return float
     */
    public function getErp()
    {
        return $this->erp;
    }

    /**
     * @param float $erp
     * @return EnvironmentOde
     */
    public function setErp($erp)
    {
        $this->erp = $erp;
        return $this;
    }

    /**
     * @return float
     */
    public function getCfm()
    {
        return $this->cfm;
    }

    /**
     * @param float $cfm
     * @return EnvironmentOde
     */
    public function setCfm($cfm)
    {
        $this->cfm = $cfm;
        return $this;
    }

    /**
     * @return float
     */
    public function getGravity()
    {
        return $this->gravity;
    }

    /**
     * @param float $gravity
     * @return EnvironmentOde
     */
    public function setGravity($gravity)
    {
        $this->gravity = $gravity;
        return $this;
    }
}