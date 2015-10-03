<?php

namespace Zantolov\MediaBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Zantolov\AppBundle\Controller\EntityCrudController;
use Zantolov\MediaBundle\Entity\Image;
use Zantolov\MediaBundle\Form\ImageType;

/**
 * Image controller.
 *
 * @Route("/image")
 */
class ImageController extends EntityCrudController
{
    protected function getEntityClass()
    {
        return 'ZantolovMediaBundle:Image';
    }

    /**
     * @param $string
     * @return String
     */
    protected function translate($string)
    {
        return $this->get('translator')->trans($string);
    }

    /**
     * Lists all Image entities.
     *
     * @Route("/", name="media.image")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return parent::baseIndexAction($request);
    }

    /**
     * Creates a new Image entity.
     *
     * @Route("/", name="media.image.create")
     * @Method("POST")
     * @Template("ZantolovMediaBundle:Image:new.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::baseCreateAction($request, new Image(), 'media.image.show');
    }

    /**
     * Creates a form to create a Image entity.
     *
     * @param Image $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm($entity)
    {
        return parent::createBaseCreateForm($entity, new ImageType(), $this->generateUrl('media.image.create'));
    }

    /**
     * Displays a form to create a new Image entity.
     *
     * @Route("/new", name="media.image.new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        return parent::baseNewAction(new Image());
    }

    /**
     * Finds and displays a Image entity.
     *
     * @Route("/{id}", name="media.image.show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        return parent::baseShowAction($id);
    }

    /**
     * Displays a form to edit an existing Image entity.
     *
     * @Route("/{id}/edit", name="media.image.edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        return parent::baseEditAction($id);
    }

    /**
     * Creates a form to edit a Image entity.
     *
     * @param Image $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createEditForm($entity)
    {
        return parent::createBaseCreateForm($entity, new ImageType(), $this->generateUrl('media.image.update', array('id' => $entity->getId())));
    }

    /**
     * Edits an existing Image entity.
     *
     * @Route("/{id}", name="media.image.update")
     * @Method("PUT")
     * @Template("ZantolovMediaBundle:Image:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::baseUpdateAction($request, $id, $this->generateUrl('media.image.edit', array('id' => $id)));
    }

    /**
     * Deletes a Image entity.
     *
     * @Route("/{id}", name="media.image.delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::baseDeleteAction($request, $id, $this->generateUrl('media.image'));
    }

    /**
     * Creates a form to delete a Image entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id)
    {
        return parent::baseCreateDeleteForm($this->generateUrl('media.image.delete', array('id' => $id)));
    }


    /**
     * Lists all Image entities.
     *
     * @Route("/browse", name="media.image.popup.browse")
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
}
