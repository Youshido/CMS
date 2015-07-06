<?php
/*
 * This file is a part of cms project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 1:18 AM 6/29/15
 */

namespace Youshido\CMSBundle\Structure\Attribute;


interface AttributedInterface
{

    public function setAttributes($attributes);
    public function applyAttributesToObject();
    public function getAttributesViewValues();

    public function getAttributes();
    public function getAttributesFormFields();
    function refreshAttributes();

}