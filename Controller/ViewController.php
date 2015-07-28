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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Youshido\CMSBundle\Entity\View\ContentView;
use Youshido\CMSBundle\Service\AttributeService;

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

    /**
     * @Route("/cms/get-attribute-form", name="cms.addAttributeForm")
     * @param Request $request
     * @return Response
     */
    public function addAttributeAction(Request $request)
    {
        $type = $request->get('type');

        if(in_array($type, array_keys(AttributeService::getAvailableTypes()))){
            return $this->render(sprintf('YCMSBundle:_attributes:%s.html.twig', $type));
        }else{
            throw new NotFoundHttpException();
        }
    }
}
