<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Configuration;
use AppBundle\Entity\Dlc;
use AppBundle\Entity\DlcType;
use AppBundle\Repository\RepositoryAwareTrait;
use Application\Sonata\UserBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ModelController extends FOSRestController
{
    use RepositoryAwareTrait;

    const PER_PAGE = 20;

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $filters = $request->get('filters', []);
        $currentPage = $request->get('page', 1);
        $state = $request->get('status', '');
        /** @var User $user */
        $user = $this->getUser();

        if (!$user->canViewModels()) {
            return $this->redirectToRoute('experiments_list');
        }

        $modelFilters = $this->getDlcRepository()->findAll();

        $models = $this->getDlcRepository()->getAvailableModels($filters, $currentPage, self::PER_PAGE);

        $maxRows = $models->count();
        $maxPages = ceil($maxRows / self::PER_PAGE);
        $types = $this->getDlcTypeRepository()->findAll();

        return $this->render('models/list.html.twig', [
            'models' => $models,
            'modelFilters' => $modelFilters,
            'filters' => $filters,
            'currentPage' => $currentPage,
            'maxPages' => $maxPages,
            'maxRows' => $maxRows,
            'types' => $types,
            'state' => $state
        ]);
    }

    /**
     * @Route("/ui/models/add", name="add_model")
     */
    public function addModelAction(Request $request)
    {
        $file = $request->files->get('model');
        $state = $this->processModel($file) ? 'success' : 'warning';

        return $this->redirectToRoute('homepage', ['status' => $state]);
    }

    /**
     * @Route("/ui/models/{id}/edit", name="edit_model")
     */
    public function editModelAction(Request $request)
    {
        $modelId = $request->get('id');
        /** @var Dlc $model */
        $model = $this->getDlcRepository()->find($modelId);

        $model->setActive(!$model->isActive());

        $em = $this->getEm();
        $em->persist($model);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @param UploadedFile $file
     * @return bool
     */
    protected function processModel(UploadedFile $file)
    {
        $fileName =  mb_ereg_replace('([\.]{2,})', '', $file->getClientOriginalName());;
        $dir = $this->moveFile($file);

        $zip = new \ZipArchive();

        if ($zip->open($dir . '/' . $fileName) === TRUE ) {
            $zip->extractTo($dir);
            $zip->close();

            $em = $this->getDoctrine()->getManager();

            $manifest = json_decode(file_get_contents($dir . '/' . 'manifest.json'), true);

            $dlc = new Dlc();

            $type = $em->getRepository(DlcType::class)->findOneBy(['value' => $manifest['type']]);

            $dlcExists = $em->getRepository(Dlc::class)->findOneBy([
                'bundle' => $manifest['bundle'],
                'hash' => $manifest['hash']
            ]);

            if (!$dlcExists) {
                if ($this->ftpUpload($dir . '/' . $manifest['file'], $manifest['type'], $manifest['file'])) {
                    $dlc
                        ->setActive(true)
                        ->setType($type)
                        ->setVersion(isset($manifest['version']) ? $manifest['version'] : '0')
                        ->setBundle($manifest['bundle'])
                        ->setHash($manifest['hash'])
                        ->setName($manifest['name'])
                        ->setPath('content/' . $manifest['type'] . 's/' . $manifest['file']);

                    $em->persist($dlc);
                    $em->flush();
                }

                $this->cleanUpDir($dir, $manifest, $fileName);

                return true;
            } else {
                $this->cleanUpDir($dir, $manifest, $fileName);

                return false;
            }
        }

        return false;
    }


    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function moveFile(UploadedFile $file)
    {
        $dirName = uniqid();
        $fileName = $file->getClientOriginalName();
        $storedFileName = $fileName;

        $basePath = $this->getParameter('models_root_dir') . '/' . $dirName;

        $file->move(
            $basePath,
            $storedFileName
        );

        return $basePath;
    }

    /**
     * @param string $localFile
     * @param string $contentType
     * @param string $remoteFile
     * @return bool
     */
    protected function ftpUpload($localFile, $contentType, $remoteFile)
    {
        $confRepo = $this->getDoctrine()->getRepository(Configuration::class);
        $ftpHost = $confRepo->findOneBy(['key' => 'ftp_host']);
        $ftpLogin = $confRepo->findOneBy(['key' => 'ftp_login']);
        $ftpPassword = $confRepo->findOneBy(['key' => 'ftp_password']);

        $connId = ftp_connect($ftpHost->getValue());

        ftp_login($connId, $ftpLogin->getValue(), $ftpPassword->getValue());

        try {
            ftp_pasv($connId, true);
            ftp_chdir($connId, "content");
            ftp_chdir($connId, $contentType . 's');

            ftp_put($connId, $remoteFile, $localFile, FTP_BINARY);
            ftp_close($connId);

            return true;
        } catch (\Exception $ex) {
            // Deal with it
        }

        ftp_close($connId);
        return false;
    }

    /**
     * @param $dir
     * @param $manifest
     * @param $fileName
     */
    protected function cleanUpDir($dir, $manifest, $fileName)
    {
        unlink($dir . '/' . $manifest['file']);
        unlink($dir . '/' . 'manifest.json');
        unlink($dir . '/' . $fileName);
        rmdir($dir);
    }
}