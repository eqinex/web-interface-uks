<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Dlc;
use AppBundle\Entity\DlcType;
use AppBundle\Entity\EnvironmentOde;
use AppBundle\Repository\RepositoryAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class EnvironmentController extends FOSRestController
{
    use RepositoryAwareTrait;

    const PER_PAGE = 20;

    /**
     * @Route("/ui/environment", name="environment_list")
     */
    public function listAction(Request $request)
    {
        $filters = $request->get('filters', []);
        $currentPage = $request->get('page', 1);

        $type = $this->getDlcTypeRepository()->findOneBy(['value' => DlcType::TYPE_ENVIRONMENT ]);
        $object = $this->getDlcTypeRepository()->findOneBy(['value' => DlcType::TYPE_OBJECT ]);
        $robot = $this->getDlcTypeRepository()->findOneBy(['value' => DlcType::TYPE_ROBOT ]);

        $filters['type'] = $type;
        $environments = $this->getDlcRepository()->getAvailableEnvironments($filters, $currentPage, self::PER_PAGE);

        $maxRows = $environments->count();
        $maxPages = ceil($maxRows / self::PER_PAGE);

        $items = $this->getDlcRepository()->findBy([
            'type' => [$object, $robot],
            'active' => 1
        ]);
        return $this->render('environment/list.html.twig', [
            'environments' => $environments,
            'items' => $items,
            'filters' => $filters,
            'currentPage' => $currentPage,
            'maxPages' => $maxPages,
            'maxRows' => $maxRows,
            'defaultEnvironmentOde' => new EnvironmentOde()
        ]);
    }

    /**
     * @Route("/ui/environment/{id}/add-items", name="add_items")
     */
    public function addItemsAction(Request $request)
    {
        $environmentId = $request->get('id');
        /** @var Dlc $environment */
        $environment = $this->getDlcRepository()->find($environmentId);
        $items = $request->get('items',[]);

        foreach ($environment->getItems() as $item) {
            $environment->removeItem($item);
        }

        $this->getEm()->persist($environment);
        $this->getEm()->flush();

        foreach ($items as $itemId) {
            $item = $this->getDlcRepository()->find($itemId);
            $environment->addItem($item);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($environment);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("ui/environment/{id}/edit-settings", name="edit_settings_environment")
     */
    public function editSettingsAction(Request $request)
    {
        $environmentId = $request->get('id');
        /** @var Dlc $environment */
        $environment = $this->getDlcRepository()->find($environmentId);
        $settingsDetails = $request->get('settings');

        /** @var EnvironmentOde $settings */
        $settings = $environment->getEnvironmentsOde() ? : new EnvironmentOde();

        $settings
            ->setEnvironment($environment)
            ->setCfm($settingsDetails['cfm'])
            ->setCore($settingsDetails['core'])
            ->setErp($settingsDetails['erp'])
            ->setGravity($settingsDetails['gravity'])
            ->setIterations($settingsDetails['iterations'])
            ->setSps($settingsDetails['sps'])
            ->setStepRate($settingsDetails['stepRate'])
            ->setStepTime($settingsDetails['stepTime'])
        ;

        $em = $this->getDoctrine()->getManager();
        $em->persist($settings);
        $em->flush();

        $environment->setEnvironmentsOde($settings);
        $em->persist($environment);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}