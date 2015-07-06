<?php
/*
 * This file is a part of cms project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 1:35 AM 7/1/15
 */

namespace Youshido\CMSBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Youshido\CMSBundle\Entity\View\ContentView;

class ViewController extends Controller
{

    /**
     * @param $id
     * @Route("/cms/add-custom-view/{id}", name="cms.addCustomView")
     * @return JsonResponse
     */
    public function addCustomViewAction($id)
    {
        $object = $this->getDoctrine()->getRepository('YCMSBundle:View\View')->find($id);
        return new JsonResponse('hello');
    }

}