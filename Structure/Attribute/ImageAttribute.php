<?php
/*
 * This file is a part of cms project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 11:39 PM 6/24/15
 */

namespace Youshido\CMSBundle\Structure\Attribute;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Youshido\UploadableBundle\Annotations as Youshido;

class ImageAttribute extends FileAttribute
{

    protected $type = "image";

    /**
     * @var string
     *
     * @Youshido\Uploadable(override="true", asserts={@Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Please upload a valid Image"
     * )})
     */
    protected $value;

    public function getFormWidgetInfo()
    {
        $options = parent::getFormWidgetInfo();

        $options[$this->name]['entity'] = 'Youshido\\CMSBundle\\Structure\\Attribute\\ImageAttribute';
        $options[$this->name]['entity_property'] = "value";

        return $options;
    }

}