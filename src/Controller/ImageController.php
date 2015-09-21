<?php

namespace Zantolov\MediaBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Zantolov\MediaBundle\Entity\Image;
use Zantolov\MediaBundle\Form\ImageType;

/**
 * Image controller.
 *
 * @Route("/image")
 */
class ImageController extends Controller
{
    /**
     * @param $string
     * @return String
     */
    protected function translate($string){
        return $this->get('translator')->trans($string);
    }
    /**
     * Lists all Image entities.
     *
     * @Route("/", name="media.image")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ZantolovMediaBundle:Image')->findAll();

        return array(
            'entities' => $entities,
        );
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
        $entity = new Image();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $created = $this->translate('Image Created');
            $this->get('session')->getFlashBag()->add('success', $created);


            return $this->redirect($this->generateUrl('media.image.show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Image entity.
     *
     * @param Image $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Image $entity)
    {
        $form = $this->createForm(new ImageType(), $entity, array(
            'action' => $this->generateUrl('media.image.create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-success btn-lg')));

        return $form;
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
        $entity = new Image();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ZantolovMediaBundle:Image')->find($id);

        if (!$entity) {
            $notFound = $this->translate('Unable to find Image entity');
            throw $this->createNotFoundException($notFound);
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ZantolovMediaBundle:Image')->find($id);

        if (!$entity) {
            $notFound = $this->translate('Unable to find Image entity');
            throw $this->createNotFoundException($notFound);
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Image entity.
     *
     * @param Image $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Image $entity)
    {
        $form = $this->createForm(new ImageType(), $entity, array(
            'action' => $this->generateUrl('media.image.update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-success')));

        return $form;
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ZantolovMediaBundle:Image')->find($id);

        if (!$entity) {
            $notFound = $this->translate('Unable to find Image entity');
            throw $this->createNotFoundException($notFound);
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $updated = $this->translate('Image Updated');
            $this->get('session')->getFlashBag()->add('success', $updated);


            return $this->redirect($this->generateUrl('media.image.edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Image entity.
     *
     * @Route("/{id}", name="media.image.delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ZantolovMediaBundle:Image')->find($id);

            if (!$entity) {
                $notFound = $this->translate('Unable to find Image entity');
                throw $this->createNotFoundException($notFound);
            }

            $em->remove($entity);
            $em->flush();
            $deleted = $this->translate('Image Deleted');
            $this->get('session')->getFlashBag()->add('success', $deleted);

        }

        return $this->redirect($this->generateUrl('media.image'));
    }

    /**
     * Creates a form to delete a Image entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('media.image.delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm();
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
