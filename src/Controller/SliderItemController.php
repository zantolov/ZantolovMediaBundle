<?php

namespace Zantolov\MediaBundle\Controller;

use Zantolov\AppBundle\Controller\EntityCrudController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Zantolov\MediaBundle\Entity\SliderItem;
use Zantolov\MediaBundle\Form\SliderItemType;

/**
 * SliderItem controller.
 *
 * @Route("/slider-item")
 */
class SliderItemController extends EntityCrudController
{

    protected $enabledFilters = array('slider');

    protected function getEntityClass()
    {
        return 'ZantolovMediaBundle:SliderItem';
    }

    /**
     * Lists all SliderItem entities.
     *
     * @Route("/", name="zantolov.media.slider-item.index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $sliders = $this->getDoctrine()->getManager()->getRepository('ZantolovMediaBundle:Slider')->findAll();
        return array_merge(parent::baseIndexAction($request, $this->processFilters()), compact('sliders'));
    }

    /**
     * Creates a new SliderItem entity.
     *
     * @Route("/", name="zantolov.media.slider-item.create")
     * @Method("POST")
     * @Template("ZantolovMediaBundle:SliderItem:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SliderItem();
        $form = $this->createCreateForm($entity)->handleRequest($request);

        if ($form->isValid()) {
            $this->getManager()->persist($entity);
            $this->getManager()->flush();
            $created = $this->translate('Slider Item Created');
            $this->get('session')->getFlashBag()->add('success', $created);

            return $this->redirect($this->generateUrl('zantolov.media.slider-item.show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SliderItem entity.
     *
     * @param SliderItem $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm($entity)
    {
        $form = $this->createForm(new SliderItemType(), $entity, array(
            'action' => $this->generateUrl('zantolov.media.slider-item.create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-success btn-lg')));

        return $form;
    }

    /**
     * Displays a form to create a new SliderItem entity.
     *
     * @Route("/new", name="zantolov.media.slider-item.new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SliderItem();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SliderItem entity.
     *
     * @Route("/{id}", name="zantolov.media.slider-item.show", requirements={"id"="\d+"})
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
     * Displays a form to edit an existing SliderItem entity.
     *
     * @Route("/{id}/edit", name="zantolov.media.slider-item.edit")
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
     * Creates a form to edit a SliderItem entity.
     *
     * @param SliderItem $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createEditForm($entity)
    {
        $form = $this->createForm(new SliderItemType(), $entity, array(
            'action' => $this->generateUrl('zantolov.media.slider-item.update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Edits an existing SliderItem entity.
     *
     * @Route("/{id}", name="zantolov.media.slider-item.update")
     * @Method("PUT")
     * @Template("ZantolovMediaBundle:SliderItem:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->getEntityById($id);

        $editForm = $this->createEditForm($entity)->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getManager()->flush();

            $updated = $this->translate('Slider Item Updated');
            $this->get('session')->getFlashBag()->add('success', $updated);

            return $this->redirect($this->generateUrl('zantolov.media.slider-item.edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }

    /**
     * Deletes a SliderItem entity.
     *
     * @Route("/{id}", name="zantolov.media.slider-item.delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id)->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->getEntityById($id);

            $this->getManager()->remove($entity);
            $this->getManager()->flush();

            $deleted = $this->translate('Slider Item Deleted');
            $this->get('session')->getFlashBag()->add('success', $deleted);

        }

        return $this->redirect($this->generateUrl('slider-item'));
    }

    /**
     * Creates a form to delete a SliderItem entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('zantolov.media.slider-item.delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm();
    }

    /**
     * @Route("/reorder/{id}/{direction}", name="zantolov.media.slider-item.reorder")
     * @Method("GET")
     */
    public function reorderAction(Request $request, $id, $direction)
    {
        return parent::baseReorderAction($request, $id, $direction);
    }


}
