<?php

namespace Zantolov\MediaBundle\Controller;

use Zantolov\AppBundle\Controller\EntityCrudController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Zantolov\MediaBundle\Entity\Slider;
use Zantolov\MediaBundle\Form\SliderType;

/**
 * Slider controller.
 *
 * @Route("/slider")
 */
class SliderController extends EntityCrudController
{

    protected function getEntityClass()
    {
        return 'ZantolovMediaBundle:Slider';
    }

    /**
     * Lists all Slider entities.
     *
     * @Route("/", name="zantolov.media.slider.index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {


        return array(
            'entities' => $this->getRepository()->findAll(),
        );
    }

    /**
     * Creates a new Slider entity.
     *
     * @Route("/", name="zantolov.media.slider.create")
     * @Method("POST")
     * @Template("ZantolovMediaBundle:Slider:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Slider();
        $form = $this->createCreateForm($entity)->handleRequest($request);

        if ($form->isValid()) {
            $this->getManager()->persist($entity);
            $this->getManager()->flush();
            $created = $this->translate('Slider Created');
            $this->get('session')->getFlashBag()->add('success', $created);

            return $this->redirect($this->generateUrl('zantolov.media.slider.show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Slider entity.
     *
     * @param Slider $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm($entity)
    {
        $form = $this->createForm(new SliderType(), $entity, array(
            'action' => $this->generateUrl('zantolov.media.slider.create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-success btn-lg')));

        return $form;
    }

    /**
     * Displays a form to create a new Slider entity.
     *
     * @Route("/new", name="zantolov.media.slider.new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Slider();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Slider entity.
     *
     * @Route("/{id}", name="zantolov.media.slider.show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getEntityById($id);


        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Slider entity.
     *
     * @Route("/{id}/edit", name="zantolov.media.slider.edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->getEntityById($id);

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Slider entity.
     *
     * @param Slider $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createEditForm($entity)
    {
        $form = $this->createForm(new SliderType(), $entity, array(
            'action' => $this->generateUrl('zantolov.media.slider.update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Edits an existing Slider entity.
     *
     * @Route("/{id}", name="zantolov.media.slider.update")
     * @Method("PUT")
     * @Template("ZantolovMediaBundle:Slider:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->getEntityById($id);

        $editForm = $this->createEditForm($entity)->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getManager()->flush();
            $updated = $this->translate('Slider Updated');
            $this->get('session')->getFlashBag()->add('success', $updated);

            return $this->redirect($this->generateUrl('zantolov.media.slider.edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }

    /**
     * Deletes a Slider entity.
     *
     * @Route("/{id}", name="zantolov.media.slider.delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id)->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->getEntityById($id);

            $this->getManager()->remove($entity);
            $this->getManager()->flush();

            $deleted = $this->translate('Slider Deleted');
            $this->get('session')->getFlashBag()->add('success', $deleted);

        }

        return $this->redirect($this->generateUrl('zantolov.media.slider.index'));
    }

    /**
     * Creates a form to delete a Slider entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('zantolov.media.slider.delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm();
    }


    /**
     * @Template()
     */
    public function displaySliderAction($id)
    {
        $slider = $this->getEntityById($id);
        return compact('slider');

    }

}
