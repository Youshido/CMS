<?php
/**
 * Created by PhpStorm.
 * User: mounter
 * Date: 6/30/15
 * Time: 5:56 PM
 */

namespace Youshido\CMSBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Youshido\CMSBundle\Entity\View\ContentView;
use Youshido\CMSBundle\Entity\View\View;

class StructureService
{
    use ContainerAwareTrait;

    protected $_viewTypes = [
        'collection_view' => [
            'id'          => 'collection_view',
            'class'       => 'Youshido\CMSBundle\Entity\View\CollectionView',
            'objectTitle' => 'Collection View',
            'type' => 'collection_view'
        ],
    ];

    public function saveHandler(View $object, Request $request)
    {
        $objectSubviews = new ArrayCollection();
        if (!($subviews = $request->get('viewsSerialized'))) return true;
        $subviews = json_decode($subviews);
        /**
         * @var EntityManager $em
         */
        $em = $this->container->get('doctrine')->getManager();

        $updatedSubViews = [];
        foreach ($subviews as $viewSource) {
            if (!($view = $object->findSubviewWithId($viewSource->id))) {
                switch(true){
                    case $viewSource->type == View::TYPE_CONTENT_TYPE:
                        $source = $this->container->get('doctrine')->getRepository('YCMSBundle:View\ContentType')->find($viewSource->id);

                        $view   = new ContentView();
                        $view->setAttributes($source->getAttributes());

                        $view->setSourceContentType($source);

                        break;

                    case $viewSource->type == View::TYPE_CONTENT_VIEW:
                        $source = $this->container->get('doctrine')->getRepository('YCMSBundle:View\ContentView')->find($viewSource->id);
                        $view   = clone $source;
                        $view->setSourceView($source);
                        $view->loadAttributesDefaultValues();
                        break;

                    case $viewSource->type == View::TYPE_COLLECTION_VIEW:
                        if(array_key_exists($viewSource->id, $this->_viewTypes)){
                            $class = $this->_viewTypes[$viewSource->id]['class'];
                            $view = new $class();
                        }else{
                            throw new \Exception();
                        }
                        break;
                }

                $view->setParent($object);
                $view->setObjectTitle($viewSource->name);
                $view->attributesForm = $this->container->get('cms.form.helper')->getVarsFormForAttributes($view);
            }else{
                $updatedSubViews[] = $view->getId();
            }
            $view->setName($viewSource->name);
            $objectSubviews->add($view);
        }


        //removing subviews
        foreach($object->getSubviews() as $subview){
            if(!in_array($subview->getId(), $updatedSubViews)){
                $em->remove($subview);
            }
        }

        foreach ($objectSubviews as $key => $view) {
            /**
             * @var View $view
             */
            $view->attributesForm->handleRequest($request);

            $this->container->get('cms.attribute')->parseAttributesFromForm($view, $view->attributesForm);
            $view->setPosition($key);
            $em->persist($view);
            $em->flush();
        }

    }

    public function loadEditorTab()
    {
        $adminContext = $this->container->get('adminContext');

        $module = $adminContext->getActiveModule();
        $adminContext->updateModuleStructure($module['name'], [
            'structure' => [
                'title'    => 'Structure',
                'template' => '@YAdmin/_fragments/structure.html.twig',
            ],
        ], 'tabs');
    }

    public function loadHandler(View $object, Request $request)
    {
        foreach ($object->getSubviews() as $view) {
            $view->attributesForm = $this->container->get('cms.form.helper')->getVarsFormForAttributes($view);
        }
        return [
            'viewsSource' => $this->getAvailableSubviewsForObject($object)
        ];
    }

    public function renderHandler(View $object, Request $request)
    {
        foreach ($object->getSubviews() as $view) {
            $view->attributesForm = $view->attributesForm->createView();
        }
    }

    public function getAvailableSubviewsForObject(View $object)
    {
        if (!$object->isSubviewsAllowed()) return [];

        if (method_exists($object, 'getAvailableSubviews')) {
            return $object->getAvailableSubviews($this);
        } else {
            return $this->getContentViews();
        }
    }

    public function getContentTypeInstanceByName($name)
    {
        return $this->container->get('doctrine')->getRepository('YCMSBundle:View\ContentType')->findOneBy(['name' => $name]);
    }

    private function getContentViews()
    {
        $contentViews = $this->container->get('doctrine')->getRepository('YCMSBundle:View\ContentView')->loadSourceList();

        $contentViews += $this->_viewTypes;
        return $contentViews;
    }

}