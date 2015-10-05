<?php

namespace Zantolov\MediaBundle\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Validator\Validator;
use Zantolov\AppBundle\Controller\DefaultEntityCrudController;
use Zantolov\MediaBundle\Entity\Document;
use Zantolov\MediaBundle\Form\DocumentType;
use Zantolov\MediaBundle\Service\NamerTrait;

/**
 * @Route("/document")
 */
class DocumentController extends DefaultEntityCrudController
{
    use NamerTrait;

    const ROUTE_PREFIX = 'zantolov.media.document.';

    protected function getEntityClass()
    {
        return 'ZantolovMediaBundle:Document';
    }

    /**
     * @return Document
     */
    public function getNewEntity()
    {
        return new Document();
    }

    /**
     * @return DocumentType
     */
    public function getNewEntityType()
    {
        return new DocumentType();
    }

    /**
     * @Route("/", name="zantolov.media.document.index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return parent::baseIndexAction($request);
    }

    /**
     * @Route("/", name="zantolov.media.document.create")
     * @Method("POST")
     * @Template("ZantolovMediaBundle:Document:new.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }


    /**
     * @Route("/new", name="zantolov.media.document.new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        return parent::newAction();
    }


    /**
     * @Route("/{id}", name="zantolov.media.document.show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        return parent::showAction($id);
    }


    /**
     * @Route("/{id}/edit", name="zantolov.media.document.edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        return parent::editAction($id);
    }


    /**
     * @Route("/{id}", name="zantolov.media.document.update")
     * @Method("PUT")
     * @Template("ZantolovMediaBundle:Document:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }


    /**
     * @Route("/{id}", name="zantolov.media.document.delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }


    /**
     * @return string
     */
    private function getTargetDirectory()
    {
        return $this->getParameter('kernel.root_dir') . '/../web/' . $this->getParameter('uploads_dir') . '/files/';
    }

    /**
     * Lists all Image entities.
     *
     * @Route("/upload", name="zantolov.media.document.upload")
     * @Method("POST")
     * @Template()
     */
    public function uploadProcessAction(Request $request)
    {
        if (
            empty($_FILES) &&
            empty($_POST) &&
            isset($_SERVER['REQUEST_METHOD']) &&
            strtolower($_SERVER['REQUEST_METHOD']) == 'post'
        ) {
            // catch file overload error...
            $postMax = ini_get('post_max_size'); //grab the size limits...
            $uploadMax = ini_get('upload_max_filesize');
            $min = min($postMax, $uploadMax);
            return new Response("Files larger than {$min} are not allowed!", 413);
        }

        $files = $request->files->all();
        $uploadsDir = $this->getTargetDirectory();

        /** @var UploadedFile $file */
        foreach ($files as $file) {
            try {

                $name = $this->getFileName($file->getClientOriginalName());
                if ($extension = $this->getExtension($file)) {
                    $name = sprintf('%s.%s', $name, $extension);
                }

                $file = $file->move($uploadsDir, $name);
                $entity = $this->getNewEntity();
                $entity->setActive(true);
                $entity->setFile($file);
                $entity->setFilename($name);

                /** @var Validator $validator */
                $validator = $this->get('validator');
                $errors = $validator->validate($entity);

                if ($errors->count() > 0) {
                    return new Response($errors->get(0)->getMessage(), 400);
                }

                $this->getManager()->persist($entity);

            } catch (FileException $e) {
                return new Response($e->getMessage(), 400);
            }

        }

        $this->getManager()->flush();


        return new Response('OK');
    }


    /**
     * Lists all Image entities.
     *
     * @Route("/browse/ckeditor", name="zantolov.media.document.popup.browse.ckeditor")
     * @Method("GET")
     * @Template()
     */
    public function ckeditorBrowseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ZantolovMediaBundle:Document')->findBy(array('active' => 1));

        $callback = $request->get('CKEditorFuncNum');

        $paginator = $this->get('knp_paginator');
        $files = $paginator->paginate(
            $entities,
            $request->query->getInt('page', 1), // page number,
            50 // limit per page
        );

        $disableNavigation = true;
        return compact('files', 'callback', 'disableNavigation');
    }


    /**
     * Routing defined in routing.yml
     * @param $filename
     */
    public function downloadAction($filename)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('ZantolovMediaBundle:Document')->findOneBy(array('active' => 1, 'filename' => $filename));

        if (empty($file)) {
            throw $this->createNotFoundException();
        }

        $path = $this->getTargetDirectory() . $filename;

        if (file_exists($path)) {
            $response = new Response(file_get_contents($path));
            $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename));
            return $response;
        } else {
            throw $this->createNotFoundException();

        }

    }

}
