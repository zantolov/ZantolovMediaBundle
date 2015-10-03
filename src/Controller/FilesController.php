<?php

namespace Zantolov\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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


    /**
     * Lists all Image entities.
     *
     * @Route("/upload", name="media.files.upload.process")
     * @Method("POST")
     * @Template()
     */
    public function uploadProcessAction(Request $request)
    {
        if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { //catch file overload error...
            $postMax = ini_get('post_max_size'); //grab the size limits...
            return new JsonResponse(array(
                'message' => "Files larger than {$postMax} are not allowed!",
            ), 413);
        }

        $files = $request->files->all();

        return new JsonResponse();
    }

}