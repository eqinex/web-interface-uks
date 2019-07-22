<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Attempt;
use AppBundle\Entity\Configuration;
use AppBundle\Entity\Dlc;
use AppBundle\Entity\DlcType;
use AppBundle\Entity\EnvironmentOde;
use AppBundle\Entity\Experiment;
use AppBundle\Entity\ExperimentEvaluation;
use AppBundle\Entity\Indicator;
use AppBundle\Entity\Joints;
use AppBundle\Entity\MedicalIndicator;
use AppBundle\Entity\Operator;
use AppBundle\Entity\Scene;
use Doctrine\Bundle\DoctrineBundle\Registry;

Trait RepositoryAwareTrait
{
    /**
     * @return Registry
     */
    abstract protected function getDoctrine();

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return OperatorRepository
     */
    protected function getOperatorRepository()
    {
        return $this->getDoctrine()->getRepository(Operator::class);
    }

    /**
     * @return ExperimentRepository
     */
    protected function getExperimentRepository()
    {
        return $this->getDoctrine()->getRepository(Experiment::class);
    }

    /**
     * @return DlcRepository
     */
    protected function getDlcRepository()
    {
        return $this->getDoctrine()->getRepository(Dlc::class);
    }

    /**
     * @return DlcTypeRepository
     */
    protected function getDlcTypeRepository()
    {
        return $this->getDoctrine()->getRepository(DlcType::class);
    }

    /**
     * @return SceneRepository
     */
    protected function getSceneRepository()
    {
        return $this->getDoctrine()->getRepository(Scene::class);
    }

    /**
     * @return AttemptsRepository
     */
    protected function getAttemptsRepository()
    {
        return $this->getDoctrine()->getRepository(Attempt::class);
    }

    /**
     * @return MedicalIndicatorRepository
     */
    protected function getMedicalIndicatorRepository()
    {
        return $this->getDoctrine()->getRepository(MedicalIndicator::class);
    }

    /**
     * @return IndicatorRepository
     */
    protected function getIndicatorRepository()
    {
        return $this->getDoctrine()->getRepository(Indicator::class);
    }

    /**
     * @return ConfigurationRepository
     */
    protected function getConfigurationRepository()
    {
        return $this->getDoctrine()->getRepository(Configuration::class);
    }

    /**
     * @return ExperimentEvaluationRepository
     */
    protected function getExperimentEvaluationRepository()
    {
        return $this->getDoctrine()->getRepository(ExperimentEvaluation::class);
    }

    /**
     * @return EnvironmentOdeRepository
     */
    protected function getEnvironmentOdeRepository()
    {
        return $this->getDoctrine()->getRepository(EnvironmentOde::class);
    }

    /**
     * @return JointsRepository
     */
    protected function getJointsRepository()
    {
        return $this->getDoctrine()->getRepository(Joints::class);
    }
}