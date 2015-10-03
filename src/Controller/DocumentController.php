<?php

namespace Zantolov\MediaBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
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
            //catch file overload error...
            $postMax = ini_get('post_max_size'); //grab the size limits...
            return new Response("Files larger than {$postMax} are not allowed!", 413);
        }

        $uploadsDir = $this->getParameter('kernel.root_dir') . '/../web/' . $this->getParameter('uploads_dir') . '/files/';
        $files = $request->files->all();

        /** @var UploadedFile $file */
        foreach ($files as $file) {

            $name = $this->getFileName($file->getClientOriginalName());
            if ($extension = $this->getExtension($file)) {
                $name = sprintf('%s.%s', $name, $extension);
            }

            $file = $file->move($uploadsDir, $name);

            $entity = $this->getNewEntity();
            $entity->setFile($file);
            $entity->setFilename($name);
            $this->getManager()->persist($entity);
        }

        $this->getManager()->flush();


        return new Response('OK');
    }
}
