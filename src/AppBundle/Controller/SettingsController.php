<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Configuration;
use AppBundle\Repository\RepositoryAwareTrait;
use Application\Sonata\UserBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SettingsController extends FOSRestController
{
    use RepositoryAwareTrait;

    /**
     * @Route("/ui/settings", name="settings")
     */
    public function listAction(Request $request)
    {
        $configurations = $this->getConfigurationRepository()->findAll();

        return $this->render('settings/list.html.twig', [
            'configurations' => $configurations
        ]);
    }

    /**
     * @Route("/ui/settings/save", name="save_settings")
     */
    public function saveConfiguration(Request $request)
    {
        $configuration = $request->get('configuration');

        foreach ($configuration as $key => $val) {
            /** @var Configuration $config */
            $config = $this->getConfigurationRepository()->findOneBy(['key' => $key]);

            $config->setValue($val);
            $this->getEm()->persist($config);
        }

        $this->getEm()->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/user-settings", name="user_settings")
     */
    public function userSettingsListAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $configurations = $this->getDoctrine()->getRepository(User::class)->findBy([
            'id' => $user
        ]);

        /** @var \DateTimeZone $timezone */
        $timezones = \DateTimeZone::listIdentifiers();

        return $this->render('settings/user/user_settings.html.twig', [
            'configurations' => $configurations,
            'timezones' => $timezones
        ]);
    }

    /**
     * @Route("/user-settings/save", name="save_user_settings")
     */
    public function userSettingsSaveAction(Request $request)
    {
        $settings = $request->get('settings');

        /** @var User $user */
        $user = $this->getUser();

        $user->setTimezone($settings['timezone']);

        $this->getEm()->persist($user);
        $this->getEm()->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}