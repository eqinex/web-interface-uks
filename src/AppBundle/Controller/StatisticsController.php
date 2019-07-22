<?php

namespace AppBundle\Controller;


use AppBundle\Repository\RepositoryAwareTrait;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class StatisticsController extends FOSRestController
{
    use RepositoryAwareTrait;

    /**
     * @Route("/ui/statistics", name="statistics_list")
     */
    public function listAction()
    {
        return $this->render('statistics/list.html.twig');
    }
}