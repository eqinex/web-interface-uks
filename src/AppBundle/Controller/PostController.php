<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Attempt;
use AppBundle\Entity\Camera;
use AppBundle\Entity\Dlc;
use AppBundle\Entity\Experiment;
use AppBundle\Entity\Operator;
use AppBundle\Entity\Scene;
use AppBundle\Entity\Content;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Response;

class PostController extends FOSRestController
{
    /**
     * @Post("/scenes")
     */
    public function sceneAction(Request $request)
    {
        $scenesData = json_decode($request->getContent(), true);

        $resp = [];
        foreach ($scenesData as $sceneData) {
            $resp[] = $this->updateScene($sceneData);
        }

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @Post("/content")
     */
    public function sceneContentAction(Request $request)
    {
        $contents = json_decode($request->getContent(), true);
        /** @var Scene $scene */
        $scene = $this->getDoctrine()->getRepository(Scene::class)->find($request->get('scene'));

        $this->updateContent($scene, $contents);

        $resp = [];
        for ($i = 0; $i < count($contents); $i++) {
            $resp[$i]['id'] = $contents[$i]['id'];
        }

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @Post("/experiments")
     */
    public function experimentsAction(Request $request)
    {
        $contents = json_decode($request->getContent(), true);

        /** @var Operator $operator */
        $operator = $this->getDoctrine()->getRepository(Operator::class)->find($contents['operator_id']);

        /** @var Scene $scene */
        $scene = $this->getDoctrine()->getRepository(Scene::class)->find($request->get('scene'));

        $experiment = new Experiment();
        $experiment
            ->setOperator($operator)
            ->setScene($scene)
            ->setDateStart((new \DateTime())->setTimestamp($contents['date_start']))
            ->setDateStop((new \DateTime())->setTimestamp($contents['date_stop']));

        if (!empty($contents['attempts'])) {
            foreach ($contents['attempts'] as $attemptData) {
                $attempt = new Attempt();

                $attempt
                    ->setStartAt((new \DateTime())->setTimestamp($attemptData['start_at']))
                    ->setEndAt((new \DateTime())->setTimestamp($attemptData['stop_at']))
                    ->setExperiment($experiment)
                    ->setLogTransform($attemptData['log_transform']);

                $this->getDoctrine()->getManager()->persist($attempt);
            }
        }

        $this->getDoctrine()->getManager()->persist($experiment);
        $this->getDoctrine()->getManager()->flush();

        return new View(['id' => $experiment->getId()], Response::HTTP_OK);
    }

    /**
     * @Post("/experiments/log-voice")
     */
    public function experimentLogVoiceAction(Request $request)
    {
        $contents = json_decode($request->getContent(), true);

        $experimentId = $request->get('experiment');
        /** @var Experiment $experiment */
        $experiment = $this->getDoctrine()->getRepository(Experiment::class)->find($experimentId);

        if (!empty($contents['log_voice'])) {
            $experiment->setLogVoice($contents['log_voice']);
        }

        $this->getDoctrine()->getManager()->persist($experiment);
        $this->getDoctrine()->getManager()->flush();

        return new View(['id' => $experiment->getId()], Response::HTTP_OK);
    }

    /**
     * @POST("/cameras")
     */
    public function sceneCamerasAction(Request $request)
    {
        $camerasData = json_decode($request->getContent(), true);
        $sceneId = $request->get('scene');
        /** @var Scene $scene */
        $scene = $this->getDoctrine()->getRepository(Scene::class)->find($sceneId);

        $em = $this->getDoctrine()->getManager();

        foreach ($scene->getCameras() as $camera) {
            $em->remove($camera);
            $em->flush();
        }

        $resp = [];
        foreach ($camerasData as $cameraData) {
            $camera = new Camera();

            $camera
                ->setScene($scene)
                ->setName($cameraData['name'])
                ->setTransform($cameraData['transform'])
                ->setFov($cameraData['fov'])
                ->setDlc($cameraData['dlc'] > 0 ? $cameraData['dlc'] : null);

            $this->getDoctrine()->getManager()->persist($camera);
            $this->getDoctrine()->getManager()->flush();
            $this->getDoctrine()->getManager()->refresh($camera);

            $resp[] = ['id' => $camera->getId(),];
        }

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @param $sceneData
     * @return array
     */
    protected function updateScene($sceneData)
    {

        $em = $this->getDoctrine()->getManager();

        if (!empty($sceneData['id']) && $sceneData['id'] > 0) {
            $scene = $this->getDoctrine()->getRepository(Scene::class)->find($sceneData['id']);
        } else {
            $scene = new Scene();
        }

        if (isset($sceneData['name'])) {
            $scene->setName($sceneData['name']);
        }

        if (isset($sceneData['description'])) {
            $scene->setDescription($sceneData['description']);
        }

        if (isset($sceneData['version'])) {
            $scene->setVersion($sceneData['version']);
        }

        if (isset($sceneData['active'])) {
            $scene->setActive($sceneData['active']);
        }

        $em->persist($scene);
        $em->flush();

        $em->refresh($scene);

        $resp = [
            'id' => $scene->getId(),
        ];

        return $resp;
    }

    /**
     * @param Scene $scene
     * @param array $contents
     */
    protected function updateContent(Scene $scene, &$contents)
    {
        $em = $this->getDoctrine()->getManager();

        foreach ($scene->getContents() as $content) {
            $em->remove($content);
            $em->flush();
        }

        $i = 0;
        foreach ($contents as $newObj) {
            /** @var Content $content */
            $content = $em->getRepository(Content::class)->findOneBy(['scene' => $scene, 'dlc' => $newObj['dlc_id']]);

            $dlc = $em->getRepository(Dlc::class)->find($newObj['dlc_id']);
            if ($dlc) {
                $content = new Content();
                $content
                    ->setDlc($dlc)
                    ->setScene($scene)
                    ->setTransform($newObj['transform']);
            }

            $em->persist($content);
            $em->flush();

            $contents[$i]['id'] = $content->getId();
            $i++;
        }


    }
}
