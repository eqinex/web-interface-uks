<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Simulator
 *
 * @ORM\Table(name="dlc_types")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DlcTypeRepository")
 */
class DlcType
{
    const TYPE_ENVIRONMENT = 'environment';
    const TYPE_ROBOT = 'robot';
    const TYPE_OBJECT = 'object';

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
     * @ORM\Column(name="value", type="string", length=255)
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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Configuration
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}

