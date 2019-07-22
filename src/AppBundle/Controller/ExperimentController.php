<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Attempt;
use AppBundle\Entity\Configuration;
use AppBundle\Entity\Experiment;
use AppBundle\Entity\ExperimentEvaluation;
use AppBundle\Entity\MedicalIndicator;
use AppBundle\Repository\RepositoryAwareTrait;
use Application\Sonata\UserBundle\Document\User;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExperimentController extends FOSRestController
{
    use RepositoryAwareTrait;

    const PER_PAGE = 20;

    /**
     * @Route("/ui/experiments", name="experiments_list")
     */
    public function listAction(Request $request)
    {
        $filters = $request->get('filters', []);
        $currentPage = $request->get('page', 1);

        $operators = $this->getOperatorRepository()->findAll();
        $scenes = $this->getSceneRepository()->findAll();
        $experiments = $this->getExperimentRepository()->getAvailableExperiments($filters, $currentPage, self::PER_PAGE);
        /** @var User $user */
        $user = $this->getUser();
        $timezoneUser = $user->getTimezone();

        $maxRows = $experiments->count();
        $maxPages = ceil($maxRows / self::PER_PAGE);

        return $this->render('experiments/list.html.twig', [
            'experiments' => $experiments,
            'operators' => $operators,
            'scenes' => $scenes,
            'filters' => $filters,
            'currentPage' => $currentPage,
            'maxPages' => $maxPages,
            'maxRows' => $maxRows,
            'timezoneUser' => $timezoneUser
        ]);
    }

    /**
     * @Route("/ui/experiments/{id}/details", name="experiments_details")
     */
    public function detailsAction(Request $request)
    {
        $experimentId = $request->get('id');
        $experiment = $this->getExperimentRepository()->find($experimentId);
        /** @var User $user */
        $user = $this->getUser();
        $timezoneUser = $user->getTimezone();

        $attempts = $this->getAttemptsRepository()->findBy([
           'experiment' => $experiment,
            'deleted' => null
        ]);

        $indicators = $this->getIndicatorRepository()->findAll();
        $config = $this->getConfigurationRepository()->findBy([
           'key' => ['transform_view_host', 'transform_view_port']
        ]);

        $medicalIndicators = $this->getMedicalIndicatorRepository()->findBy([
           'experiment' => $experiment
        ]);

        /** @var ExperimentEvaluation $controllable */
        $controllable = $this->getExperimentEvaluationRepository()->findOneBy([
            'experiment' => $experiment,
            'key' => 'controllable'
        ]);

        /** @var ExperimentEvaluation $satisfiableQuality */
        $satisfiableQuality = $this->getExperimentEvaluationRepository()->findOneBy([
            'experiment' => $experiment,
            'key' => 'satisfiable_quality'
        ]);

        /** @var ExperimentEvaluation $desiredQuality */
        $desiredQuality = $this->getExperimentEvaluationRepository()->findOneBy([
            'experiment' => $experiment,
            'key' => 'desired_quality'
        ]);

        return $this->render('experiments/details.html.twig', [
            'experiment' => $experiment,
            'attempts' => $attempts,
            'indicators' => $indicators,
            'medicalIndicators' => $medicalIndicators,
            'controllable' => $controllable,
            'satisfiableQuality' => $satisfiableQuality,
            'desiredQuality' => $desiredQuality,
            'config' => $config,
            'timezoneUser' => $timezoneUser
        ]);
    }

    /**
     * @Route("/ui/experiments/{id}/remove-experiment", name="remove_experiment")
     */
    public function removeExperiment(Request $request)
    {
        $experimentId = $request->get('id');
        /** @var Experiment $experiment */
        $experiment = $this->getExperimentRepository()->find($experimentId);

        $experiment->setDeleted(1);

        $em = $this->getEm();
        $em->persist($experiment);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("ui/experiments/{id}/details/add-indicator", name="add_indicator")
     */
    public function addIndicatorAction(Request $request)
    {
        $experimentId = $request->get('id');
        $experiment = $this->getExperimentRepository()->find($experimentId);

        $indicatorDetails = $request->get('indicator');

        $name = $this->getIndicatorRepository()->find($indicatorDetails['name']);

        $indicator = new MedicalIndicator();
        $indicator
            ->setExperiment($experiment)
            ->setIndicator($name)
            ->setValue($indicatorDetails['value'])
        ;

        $em = $this->getEm();
        $em->persist($indicator);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("ui/experiments/{id}/details/{indicatorId}/edit-indicator", name="edit_indicator")
     */
    public function editIndicatorAction(Request $request)
    {
        $indicatorId = $request->get('indicatorId');
        /** @var MedicalIndicator $indicator */
        $indicator = $this->getMedicalIndicatorRepository()->find($indicatorId);

        $indicatorDetails = $request->get('indicator');
        $name = $this->getIndicatorRepository()->find($indicatorDetails['name']);

        $indicator
            ->setIndicator($name)
            ->setValue($indicatorDetails['value'])
        ;

        $em = $this->getEm();
        $em->persist($indicator);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("ui/experiments/{id}/details/{indicatorId}/remove-indicator", name="remove_indicator")
     */
    public function removeIndicatorAction(Request $request)
    {
        $indicatorId = $request->get('indicatorId');

        /** @var MedicalIndicator $indicator */
        $indicator = $this->getMedicalIndicatorRepository()->find($indicatorId);

        $em = $this->getEm();
        $em->remove($indicator);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/ui/experiments/{id}/details/add-evaluation", name="add_evaluation")
     */
    public function addEvaluationAction(Request $request)
    {
        $experimentId = $request->get('id');
        $experiment = $this->getExperimentRepository()->find($experimentId);
        $evaluationDetails = $request->get('evaluation');

        foreach ($evaluationDetails as $key => $value) {
            /** @var ExperimentEvaluation $evaluation */
            $evaluation = $this->getExperimentEvaluationRepository()->findOneBy([
                'experiment' => $experiment,
                'key' => $key
            ]);

            if (!$evaluation) {
                $evaluation = new ExperimentEvaluation();
                $evaluation
                    ->setExperiment($experiment)
                    ->setKey($key)
                ;
            }

            $evaluation->setValue($value);

            $this->getEm()->persist($evaluation);
        }

        $this->getEm()->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/ui/experiments/{id}/details/{attemptId}/edit-attempt", name="edit_attempt")
     */
    public function editAttemptAction(Request $request)
    {
        $attemptId = $request->get('attemptId');
        /** @var Attempt $attempt */
        $attempt = $this->getAttemptsRepository()->find($attemptId);

        $attemptDetails = $request->get('attempt');

        $attempt
            ->setSuccess($attemptDetails['success'])
            ->setDescription($attemptDetails['description'])
        ;

        $em = $this->getEm();
        $em->persist($attempt);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/ui/experiments/{id}/details/{attemptId}/remove-attempt", name="remove_attempt")
     */
    public function removeAttempt(Request $request)
    {
        $attemptId = $request->get('attemptId');
        /** @var Attempt $attempt */
        $attempt = $this->getAttemptsRepository()->find($attemptId);

        $attempt->setDeleted(1);

        $em = $this->getEm();
        $em->persist($attempt);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/ui/experiments/{id}/export-voice", name="export_voice")
     */
    public function exportVoiceAction(Request $request)
    {
        $experimentId = $request->get('id');
        /** @var Experiment $experiment */
        $experiment = $this->getExperimentRepository()->find($experimentId);

        if (!$experiment->getLogVoice()) {
            return $this->redirect($request->headers->get('referer'));
        }

        $path = $this->getParameter('models_root_dir');

        $fileName = substr(strrchr($experiment->getLogVoice(), '/'), 1);
        $remoteFile = substr(strrchr($experiment->getLogVoice(), '/'), 1);

        $confRepo = $this->getDoctrine()->getRepository(Configuration::class);
        $ftpHost = $confRepo->findOneBy(['key' => 'ftp_host']);
        $ftpLogin = $confRepo->findOneBy(['key' => 'ftp_login']);
        $ftpPassword = $confRepo->findOneBy(['key' => 'ftp_password']);

        $connId = ftp_connect($ftpHost->getValue());

        ftp_login($connId, $ftpLogin->getValue(), $ftpPassword->getValue());

        try {
            ftp_pasv($connId, true);
            ftp_chdir($connId, 'experiments');
            ftp_chdir($connId, 'logs');
            ftp_chdir($connId, 'voices');

            ftp_get($connId, $path . '/' . $fileName, $remoteFile, FTP_BINARY);

            $headers = [
                'Content-Type' => 'audio/mp3',
                'Accept-Ranges' => 'bytes',
                'Content-Length' => filesize($path . '/' . $fileName),
                'Pragma' => 'no-cache',
                'Content-Description' => 'file download',
                'Content-Disposition' => 'attachment; filename=' . $fileName,
                'Content-Transfer-Encoding' => 'binary'
            ];

            $fileContents = file_get_contents($path . '/' . $fileName);

            unlink($path . '/' . $fileName);

            ftp_close($connId);

            return new Response($fileContents, 200, $headers);
        } catch (\Exception $ex) {
            // Deal with it
        }

        ftp_close($connId);
        return false;
    }
}