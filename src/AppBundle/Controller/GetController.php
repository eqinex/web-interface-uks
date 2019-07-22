<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Camera;
use AppBundle\Entity\Configuration;
use AppBundle\Entity\Dlc;
use AppBundle\Entity\DlcType;
use AppBundle\Entity\EnvironmentOde;
use AppBundle\Entity\Experiment;
use AppBundle\Entity\Joints;
use AppBundle\Entity\Operator;
use AppBundle\Entity\Scene;
use AppBundle\Entity\Content;
use AppBundle\Repository\RepositoryAwareTrait;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;

class GetController extends FOSRestController
{
    use RepositoryAwareTrait;

    /**
     * @GET("/scenes")
     */
    public function scenesListAction(Request $request)
    {
        $params = [
            'id' => $request->get('id'),
            'name' => $request->get('name'),
            'active' => $request->get('active'),
        ];

        foreach ($params as $key => $val) {
            if (empty($val) && $val !== '0') {
                unset($params[$key]);
            }
        }

        $objects = $this->getDoctrine()->getRepository(Scene::class)->findBy($params);
        $resp = [];

        foreach ($objects as $obj) {
            $resp[] = [
                'id' => $obj->getId(),
                'name' => $obj->getName(),
                'description' => $obj->getDescription(),
                'version' => $obj->getVersion(),
                'active' => $obj->isActive(),
            ];

        }

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @GET("/content")
     */
    public function scenesContentAction(Request $request)
    {
        $sceneId = $request->get('scene');
        /** @var Scene $scene */
        $scene = $this->getDoctrine()->getRepository(Scene::class)->find($sceneId);

        $contents = [];
        foreach ($scene->getContents() as $content) {
            $contents[] = [
                'id' => $content->getId(),
                'dlc_id' => $content->getDlc()->getId(),
                'transform' => $content->getTransform(),
            ];
        }

        return new View($contents, Response::HTTP_OK);
    }

    /**
     * @GET("/env-dlc")
     */
    public function envDlcAction(Request $request)
    {
        $retValue = [];
        $id = $request->get('env');
        if (empty($id)) {
            $envDlcs = $this->getDoctrine()->getRepository(Dlc::class)->findAll();
            foreach ($envDlcs as $envDlc) {
                foreach ($envDlc->getItemsIds() as $item) {
                    $retValue[] = [
                        'env' => $envDlc->getId(),
                        'dlc' => $item
                    ];
                }
            }

        } else {
            $envDlc = $this->getDoctrine()->getRepository(Dlc::class)->find($id);
            if (!empty($envDlc)) {
                foreach ($envDlc->getItemsIds() as $item) {
                    $retValue[] = ['dlc' => $item];
                }
            }
        }

        return new View($retValue, Response::HTTP_OK);
    }

    /**
     * @GET("/dlc")
     */
    public function dlcListAction(Request $request)
    {
        $params = [
            'id' => $request->get('id'),
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'bundle' => $request->get('bundle'),
            'version' => $request->get('version'),
            'active' => $request->get('active'),
        ];

        foreach ($params as $key => $val) {
            if (empty($val) && $val !== '0') {
                unset($params[$key]);
            }
        }

        $objects = $this->getDoctrine()->getRepository(Dlc::class)->findBy($params);
        $resp = [];

        foreach ($objects as $obj) {
            $resp[] = [
                'id' => $obj->getId(),
                'type' => $obj->getType(),
                'name' => $obj->getName(),
                'bundle' => $obj->getBundle(),
                'hash' => $obj->getHash(),
                'path' => $obj->getPath(),
                'version' => $obj->getVersion(),
                'active' => $obj->isActive(),
            ];
        }

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @GET("/cameras")
     */
    public function camerasListAction(Request $request)
    {
        $params = [
            'id' => $request->get('id'),
            'name' => $request->get('name'),
            'scene' => $request->get('scene'),
        ];

        foreach ($params as $key => $val) {
            if (empty($val) && $val !== '0') {
                unset($params[$key]);
            }
        }

        $objects = $this->getDoctrine()->getRepository(Camera::class)->findBy($params);
        $resp = [];

        foreach ($objects as $obj) {
            $resp[] = [
                'id' => $obj->getId(),
                'scene' => $obj->getScene()->getId(),
                'name' => $obj->getName(),
                'transform' => $obj->getTransform(),
                'fov' => $obj->getFov(),
                'dlc' => $obj->getDlc(),
            ];
        }

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @GET("/operators")
     */
    public function operatorsListAction(Request $request)
    {
        $params = [
            'id' => $request->get('id'),
            'name' => $request->get('name'),
            'active' => $request->get('active'),
        ];

        foreach ($params as $key => $val) {
            if (empty($val) && $val !== '0') {
                unset($params[$key]);
            }
        }

        $objects = $this->getDoctrine()->getRepository(Operator::class)->findBy($params);
        $resp = [];

        foreach ($objects as $obj) {
            $resp[] = [
                'id' => $obj->getId(),
                'name' => $obj->getName(),
                'active' => $obj->isActive(),
            ];
        }

        return new View($resp, Response::HTTP_OK);
    }


    /**
     * @GET("/dlc-types")
     */
    public function dlcTypesListAction(Request $request)
    {
        $params = [
            'id' => $request->get('id'),
            'value' => $request->get('value'),
        ];

        foreach ($params as $key => $val) {
            if (empty($val) && $val !== '0') {
                unset($params[$key]);
            }
        }

        $objects = $this->getDoctrine()->getRepository(DlcType::class)->findBy($params);
        $resp = [];

        foreach ($objects as $obj) {
            $resp[] = [
                'id' => $obj->getId(),
                'value' => $obj->getValue(),
            ];
        }

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @GET("/configuration")
     */
    public function configurationListAction(Request $request)
    {
        $objects = $this->getDoctrine()->getRepository(Configuration::class)->findAll();
        $resp = [];

        foreach ($objects as $obj) {
            $resp[$obj->getKey()] = $obj->getValue();
        }

        return new View($resp, Response::HTTP_OK);
    }

    /**
     * @GET("/experiments")
     */
    public function experimentsAction(Request $request)
    {
        $id = $request->get('id');
        if (empty($id)) {
            $experiments = $this->getDoctrine()->getRepository(Experiment::class)->findAll();
        } else {
            $experiments = [$this->getDoctrine()->getRepository(Experiment::class)->find($id)];
        }

        $retValue = [];
        foreach ($experiments as $experiment) {
            $retValue[] = [
                'id' => $experiment->getID(),
                'scene' => $experiment->getScene()->getID(),
                'operator' => $experiment->getOperator()->getID(),
                'date_start' => date_timestamp_get($experiment->getDateStart()),
                'date_stop' => date_timestamp_get($experiment->getDateStop())
            ];
        }

        return new View($retValue, Response::HTTP_OK);
    }


    /**
     * @GET("/environment-ode")
     */
    public function environmentOdeAction(Request $request)
    {
        $id = $request->get('env');
        if (!empty($id)) {
            $environmentOde = $this->getDoctrine()->getRepository(EnvironmentOde::class)->findOneBy([
                'environment' => $id
            ]);
        }

        if (empty($environmentOde)) {
            $environmentOde = new EnvironmentOde();
        }

        $retValue = [
            'core' => $environmentOde->getCore(),
            'sps' => $environmentOde->getSps(),
            'step_time' => $environmentOde->getStepTime(),
            'step_rate' => $environmentOde->getStepRate(),
            'iterations' => $environmentOde->getIterations(),
            'erp' => $environmentOde->getErp(),
            'cfm' => $environmentOde->getCfm(),
            'gravity' => $environmentOde->getGravity(),
        ];

        return new View($retValue, Response::HTTP_OK);
    }

    /**
     * @GET("/joints")
     */
    public function jointsAction(Request $request)
    {
        $id = $request->get('id');
        if (empty($id)) {
            $joints = $this->getDoctrine()->getRepository(Joints::class)->findAll();
        } else {
            $joints = [$this->getDoctrine()->getRepository(Joints::class)->find($id)];
        }

        $retValue = [];
        /** @var Joints $joint */
        foreach ($joints as $joint) {
            if (!$joint->checkGrants($joint)) {
                $retValue[] = [
                    'id' => $joint->getID(),
                    'name' => $joint->getName(),
                    'gainGravity' => $joint->getGainGravity(),
                    'gainFriction' => $joint->getGainFriction()
                ];
            } else {
                $retValue[] = [
                    'id' => $joint->getID(),
                    'name' => $joint->getName(),
                    'gainGravity' => $joint->getGainGravity(),
                    'gainFriction' => $joint->getGainFriction(),
                    'deltaLength' => $joint->getDeltaLength()
                ];
            }
        }

        return new View($retValue, Response::HTTP_OK);
    }
}
