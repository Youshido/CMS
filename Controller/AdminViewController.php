<?php
/**
 * Date: 28.07.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Youshido\AdminBundle\Controller\BaseEntityController;
use Youshido\CMSBundle\Service\AttributeService;
use Youshido\CMSBundle\Structure\Attribute\AttributedInterface;

/**
 * Class ContentTypeController
 * @package Youshido\CMSBundle\Controller
 *
 */
class AdminViewController extends BaseEntityController
{

    /**
     * @Route("/cms/view/{module}/{pageNumber}", name="admin.cms-view.default", requirements={ "pageNumber" : "\d+"})
     */
    public function defaultAction(Request $request, $module, $pageNumber = 1)
    {
        return parent::defaultAction($request, $module, $pageNumber);
    }

    /**
     * @Route("/cms/view/{module}/add", name="admin.cms-view.add")
     */
    public function addAction(Request $request, $module)
    {
        return $this->processAction($module, $request);
    }

    /**
     * @Route("/cms/view/{module}/edit/{id}", name="admin.cms-view.edit")
     */
    public function editAction(Request $request, $module)
    {
        return $this->processAction($module, $request);
    }

    protected function processAction($module, Request $request)
    {
        $this->get('adminContext')->setActiveModuleName($module);
        $moduleConfig = $this->get('adminContext')->getActiveModuleForAction('add');

        /** @var AttributedInterface $object */
        $object = $this->getOrCreateObjectFromRequest($request);

        if (!$this->get('admin.security')->isGranted($object, $moduleConfig, 'add')) {
            throw new AccessDeniedException();
        }

        $form = $this->buildForm($object, $moduleConfig);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('cms.attribute')->saveAttributesStructure($object, $request);
            $this->saveValidObject($object);

            $this->addFlash('success', 'Your changes was has been saved');

            return $this->redirectToRoute($moduleConfig['actions']['edit']['route'], ['module' => $moduleConfig['name'], 'id' => $object->getId()]);
        }

        return $this->render('@YCMS/form/view.html.twig', [
            'object'             => $object,
            'form'               => $form->createView(),
            'moduleConfig'       => $moduleConfig,
            'attributes'         => $object->getAttributes(),
            'attributeTypes'     => AttributeService::getAvailableTypes()
        ]);
    }

    /**
     * @Route("/cms/view/{module}/remove/{id}", name="admin.cms-view.remove")
     */
    public function removeContentTypeAction(Request $request, $module)
    {
        parent::removeAction($module, $request);
    }

    /**
     * @Route("/cms/view/{module}/duplicate/{id}", name="admin.cms-view.duplicate")
     */
    public function duplicateContentTypeAction(Request $request, $id, $module)
    {
        parent::duplicateAction($module, $id, $request);
    }

}