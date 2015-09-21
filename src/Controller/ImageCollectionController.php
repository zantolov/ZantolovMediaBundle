<?php

namespace Zantolov\MediaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Zantolov\MediaBundle\Entity\ImageCollection;
use Zantolov\MediaBundle\Form\ImageCollectionType;

/**
 * ImageCollection controller.
 *
 * @Route("/image-collection")
 */
class ImageCollectionController extends Controller
{
    /**
     * @param $string
     * @return String
     */
    protected function translate($string){
        return $this->get('translator')->trans($string);
    }
    /**
     * Lists all ImageCollection entities.
     *
     * @Route("/", name="media.image-collection")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ZantolovMediaBundle:ImageCollection')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new ImageCollection entity.
     *
     * @Route("/", name="media.image-collection.create")
     * @Method("POST")
     * @Template("ZantolovMediaBundle:ImageCollection:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ImageCollection();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $created = $this->translate('Image Collection Created');
            $this->get('session')->getFlashBag()->add('success', $created);


            return $this->redirect($this->generateUrl('media.image-collection.show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ImageCollection entity.
     *
     * @param ImageCollection $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ImageCollection $entity)
    {
        $form = $this->createForm(new ImageCollectionType(), $entity, array(
            'action' => $this->generateUrl('media.image-collection.create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-success btn-lg')));

        return $form;
    }

    /**
     * Displays a form to create a new ImageCollection entity.
     *
     * @Route("/new", name="media.image-collection.new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ImageCollection();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ImageCollection entity.
     *
     * @Route("/{id}", name="media.image-collection.show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ZantolovMediaBundle:ImageCollection')->find($id);

        if (!$entity) {
            $notFound = $this->translate('Unable to find Image Collection entity.');
            throw $this->createNotFoundException($notFound);
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ImageCollection entity.
     *
     * @Route("/{id}/edit", name="media.image-collection.edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ZantolovMediaBundle:ImageCollection')->find($id);

        if (!$entity) {
            $notFound = $this->translate('Unable to find Image Collection entity.');
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
     * Creates a form to edit a ImageCollection entity.
     *
     * @param ImageCollection $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ImageCollection $entity)
    {
        $form = $this->createForm(new ImageCollectionType(), $entity, array(
            'action' => $this->generateUrl('media.image-collection.update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Edits an existing ImageCollection entity.
     *
     * @Route("/{id}", name="media.image-collection.update")
     * @Method("PUT")
     * @Template("ZantolovMediaBundle:ImageCollection:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ZantolovMediaBundle:ImageCollection')->find($id);

        if (!$entity) {
            $notFound = $this->translate('Unable to find Image Collection entity.');
            throw $this->createNotFoundException($notFound);
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $updated = $this->translate('Image Collection Updated');
            $this->get('session')->getFlashBag()->add('success', $updated);


            return $this->redirect($this->generateUrl('media.image-collection.edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ImageCollection entity.
     *
     * @Route("/{id}", name="media.image-collection.delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ZantolovMediaBundle:ImageCollection')->find($id);

            if (!$entity) {
                $notFound = $this->translate('Unable to find Image Collection entity.');
                throw $this->createNotFoundException($notFound);
            }

            $em->remove($entity);
            $em->flush();
            $deleted = $this->translate('Image Collection Deleted');
            $this->get('session')->getFlashBag()->add('success', $deleted);

        }

        return $this->redirect($this->generateUrl('media.image-collection'));
    }

    /**
     * Creates a form to delete a ImageCollection entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('media.image-collection.delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm();
    }
}
