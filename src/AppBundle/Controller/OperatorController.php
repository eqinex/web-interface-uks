<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Operator;
use AppBundle\Repository\RepositoryAwareTrait;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class OperatorController extends FOSRestController
{
    use RepositoryAwareTrait;

    const PER_PAGE = 20;

    /**
     * @Route("/ui/operators", name="operators_list")
     */
    public function listAction(Request $request)
    {
        $currentPage = $request->get('page', 1);

        $operators = $this->getOperatorRepository()->getAvailableOperators($currentPage, self::PER_PAGE);

        $maxRows = $operators->count();
        $maxPages = ceil($maxRows / self::PER_PAGE);

        return $this->render('operators/list.html.twig', [
            'operators' => $operators,
            'currentPage' => $currentPage,
            'maxPages' => $maxPages,
            'maxRows' => $maxRows
        ]);
    }

    /**
     * @Route("/ui/operators/add", name="add_operator")
     */
    public function addOperatorAction(Request $request)
    {
        $operatorDetails = $request->get('operator');

        $operator = new Operator();
        $operator
            ->setName($operatorDetails['name'])
            ->setActive($operatorDetails['active'])
        ;

        $em = $this->getEm();
        $em->persist($operator);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/ui/operators/{id}/edit", name="edit_operator")
     */
    public function editOperatorAction(Request $request)
    {
        $operatorId = $request->get('id');
        $operatorDetails = $request->get('operator');
        $operator = $this->getOperatorRepository()->find($operatorId);

        $operator
            ->setName($operatorDetails['name'])
            ->setActive($operatorDetails['active'])
        ;

        $em = $this->getEm();
        $em->persist($operator);
        $em->flush();

        return $this->redirectToRoute('operators_list');
    }
}