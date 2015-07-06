<?php

namespace Youshido\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Youshido\CMSBundle\Entity\Page;
use Youshido\CMSBundle\Entity\View\CollectionView;
use Youshido\CMSBundle\Structure\Attribute\TextAttribute;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$page = $this->getDoctrine()->getRepository('YCMSBundle:View\View')->findAll();
        $view = $this->getDoctrine()->getRepository('YCMSBundle:View\Slider')->findOneBy([]);
        if (!$view) {
            $view = new CollectionView();
            $view->setAttributes([new TextAttribute(['name' => 'alias'])]);
            $em->persist($view);
            $em->flush();
        }

        $view->applyAttributesToObject();
        //dump($view);die();

        $builder = $this->createFormBuilder($view)
            ->add('title');
        foreach($view->getAttributesFormFields() as $field) {
            $builder->add($field['name'], $field['type'], $field['options']);
        }
        $builder->add('submit', 'submit');
        $form = $builder->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $view->parseAttributesFromForm($form);
            $em->persist($view);
            $em->flush();
            //dump($page);die();
        }

        return $this->render('@YCMS/Default/index.html.twig', [
            'form' => $form->createView(),
            'view' => $view,
        ]);
        //$page = new Page();
        //$page->setTitle('Homepage');
    }
}
