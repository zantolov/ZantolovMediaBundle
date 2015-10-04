<?php

namespace Zantolov\MediaBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Zantolov\AppBundle\Controller\DefaultEntityCrudController;
use Zantolov\MediaBundle\Entity\Image;
use Zantolov\MediaBundle\Form\ImageType;
use Zantolov\MediaBundle\Service\NamerTrait;

/**
 * Image controller.
 *
 * @Route("/image")
 */
class ImageController extends DefaultEntityCrudController
{
    use NamerTrait;

    const ROUTE_PREFIX = 'zantolov.media.image.';

    protected function getEntityClass()
    {
        return 'ZantolovMediaBundle:Image';
    }

    /**
     * @return Image
     */
    function getNewEntity()
    {
        return new Image();
    }

    /**
     * @return ImageType
     */
    function getNewEntityType()
    {
        return new ImageType();
    }


    /**
     * Lists all Image entities.
     *
     * @Route("/", name="zantolov.media.image.index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }

    /**
     * Creates a new Image entity.
     *
     * @Route("/", name="zantolov.media.image.create")
     * @Method("POST")
     * @Template("ZantolovMediaBundle:Image:new.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * Displays a form to create a new Image entity.
     *
     * @Route("/new", name="zantolov.media.image.new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        return parent::newAction();
    }

    /**
     * Finds and displays a Image entity.
     *
     * @Route("/{id}", name="zantolov.media.image.show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        return parent::showAction($id);
    }

    /**
     * Displays a form to edit an existing Image entity.
     *
     * @Route("/{id}/edit", name="zantolov.media.image.edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        return parent::editAction($id);
    }

    /**
     * Edits an existing Image entity.
     *
     * @Route("/{id}", name="zantolov.media.image.update")
     * @Method("PUT")
     * @Template("ZantolovMediaBundle:Image:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * Deletes a Image entity.
     *
     * @Route("/{id}", name="zantolov.media.image.delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * Lists all Image entities.
     *
     * @Route("/browse", name="zantolov.media.image.popup.browse")
     * @Method("GET")
     * @Template()
     */
    public function browseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ZantolovMediaBundle:Image')->findBy(array('active' => 1));

        $selected = $request->get('selected');
        if (!empty($selected)) {
            $selected = json_decode($selected, true);
        } else {
            $selected = array();
        }

        $paginator = $this->get('knp_paginator');
        $images = $paginator->paginate(
            $entities,
            $request->query->getInt('page', 1), // page number,
            50 // limit per page
        );

        return array('images' => $images, 'selected' => $selected, 'disableNavigation' => true);
    }

    /**
     * Lists all Image entities.
     *
     * @Route("/browse/ckeditor", name="zantolov.media.image.popup.browse.ckeditor")
     * @Method("GET")
     * @Template()
     */
    public function ckeditorBrowseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ZantolovMediaBundle:Image')->findBy(array('active' => 1));

        $callback = $request->get('CKEditorFuncNum');

        $paginator = $this->get('knp_paginator');
        $images = $paginator->paginate(
            $entities,
            $request->query->getInt('page', 1), // page number,
            50 // limit per page
        );

        return array('images' => $images, 'callback' => $callback, 'disableNavigation' => true);
    }


    /**
     * Lists all Image entities.
     *
     * @Route("/upload", name="zantolov.media.image.upload")
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
            return new Response("Files larger than {$postMax} are not allowed!", 413);
        }

        $uploadsDir = $this->getParameter('kernel.root_dir') . '/../web/' . $this->getParameter('uploads_dir') . '/images/default/';
        $files = $request->files->all();

        /** @var UploadedFile $file */
        foreach ($files as $file) {

            $name = $this->getFileName($file->getClientOriginalName());
            if ($extension = $this->getExtension($file)) {
                $name = sprintf('%s.%s', $name, $extension);
            }

            $file = $file->move($uploadsDir, $name);

            $entity = $this->getNewEntity();
            $entity->setActive(true);
            $entity->setImageFile($file);
            $entity->setImageName($name);
            $this->getManager()->persist($entity);
        }

        $this->getManager()->flush();

        return new Response('OK');
    }

}
