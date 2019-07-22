<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Joints;
use AppBundle\Repository\RepositoryAwareTrait;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class JointsController extends FOSRestController
{
    use RepositoryAwareTrait;

    /**
     * @Route("/ui/joints", name="joints")
     */
    public function listAction(Request $request)
    {

        $leftJoints = $this->getJointsRepository()->getLeftJoints();
        $rightJoints = $this->getJointsRepository()->getRightJoints();

        $leftElbowR = $this->getJointsRepository()->findOneBy(['name' => 'L.ElbowR']);
        $leftWristR = $this->getJointsRepository()->findOneBy(['name' => 'L.WristR']);

        $rightElbowR = $this->getJointsRepository()->findOneBy(['name' => 'R.ElbowR']);
        $rightWristR = $this->getJointsRepository()->findOneBy(['name' => 'R.WristR']);

        return $this->render('joints/list.html.twig', [
            'leftJoints' => $leftJoints,
            'rightJoints' => $rightJoints,
            'leftElbowR' => $leftElbowR,
            'leftWristR' => $leftWristR,
            'rightElbowR' => $rightElbowR,
            'rightWristR' => $rightWristR
        ]);
    }

    /**
     * @Route("/ui/joints/save-settings", name="save_joints_settings")
     */
    public function saveJointsSettings(Request $request)
    {
        $settings = $request->get('settings');

        foreach ($settings as $name => $values) {

            /** @var Joints $joint */
            $joint = $this->getJointsRepository()->findOneBy(['name' => $name]);

            foreach ($values as $key => $value) {
                if ($key == 'gainGravity') {
                    $joint->setGainGravity($value);
                } elseif ($key == 'gainFriction') {
                    $joint->setGainFriction($value);
                } else {
                    $joint->setDeltaLength($value);
                }
            }

            $this->getEm()->persist($joint);
        }

        $this->getEm()->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}