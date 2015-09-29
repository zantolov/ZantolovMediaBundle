<?php

namespace Zantolov\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Image controller.
 *
 * @Route("/files")
 */
class FilesController extends Controller
{

    /**
     * Lists all Image entities.
     *
     * @Route("/upload", name="media.files.upload")
     * @Method("GET")
     * @Template()
     */
    public function uploadAction()
    {
        return [];
    }

}