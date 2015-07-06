<?php
/*
 * This file is a part of cms project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 12:07 AM 6/25/15
 */

namespace Youshido\CMSBundle\Service;


use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Youshido\CMSBundle\Entity\View\View;
use Youshido\CMSBundle\Structure\Attribute\AttributedInterface;
use Youshido\CMSBundle\Structure\Attribute\AttributedTrait;
use Youshido\CMSBundle\Structure\Attribute\BaseAttribute;

class AttributeService
{
    use ContainerAwareTrait;

    public function saveAttributesStructure(AttributedInterface $object, Request $request)
    {
        $object->setAttributes($object->getAttributes());
    }

    public function saveAttributesWithValues(View $object, Request $request)
    {
        $form = $this->container->get('cms.form.helper')->getVarsFormForAttributes($object);
        $form->handleRequest($request);

        $object->setAttributes($object->getAttributes());
        $this->parseAttributesFromForm($object, $form);
    }

    /**
     * @param $object AttributedTrait|View
     * @param Form $form
     */
    public function parseAttributesFromForm($object, Form $form)
    {
        $needRefresh = false;
        foreach ($object->getAttributes() as $attr) {
            /**
             * @var BaseAttribute $attr
             */
            if ($info = $form->get($attr->getName())) {
                $needRefresh = true;

                $data = $info->getData();
                if (in_array($attr->getType(), ['file', 'image'])) {
                    if ($data && $data instanceof UploadedFile) {
                        $this->container->get('youshido.uploadable.enity_manager')
                            ->saveFile($attr, 'value', $data);
                    }
                } else {
                    $attr->setValue($data);
                }
            }
        }
        if ($needRefresh) {
            $object->refreshAttributes();
        }
    }

    public function loadEditorTab($object, Request $request)
    {
        $adminContext = $this->container->get('adminContext');

        $module = $adminContext->getActiveModule();
        $adminContext->updateModuleStructure($module['name'], [
            'attributes' => [
                'type'   => 'collection',
                'form'   => 'Youshido\CMSBundle\Form\AttributeTypeForm',
                'custom' => true,
            ],
        ], 'columns');
        $adminContext->updateModuleStructure($module['name'], [
            'attributes' => [
                'title'    => 'Attributes',
                'template' => '@YAdmin/_fragments/attributes.html.twig',
            ],
        ], 'tabs');
    }

    public function loadHandler(AttributedInterface $object, Request $request)
    {
        $object->attributesForm = $this->container->get('cms.form.helper')->getVarsFormForAttributes($object)->createView();

        return [
            'attributesForm' => $object->attributesForm,
        ];
    }

    public static function getAvailableTypes()
    {
        return [
            "text"     => "Text field",
            "textarea" => "Text area",
            "image"    => "Image",
            "choice"   => "Choice",
            "hidden"   => "Hidden",
        ];
    }

    /**
     * @param $type
     * @param $info
     * @return BaseAttribute
     */
    public static function getAttributeForType($type, $info)
    {
        $className = "Youshido\\Bundle\\CMSBundle\\Structure\\Attribute\\" . ucfirst($type) . 'Attribute';
        if (class_exists($className)) {
            $object = new $className($info);
            return $object;
        }

        return null;
    }
}