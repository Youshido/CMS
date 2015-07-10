<?php
/*
 * This file is a part of cms project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 11:10 PM 6/24/15
 */

namespace Youshido\CMSBundle\Structure\Attribute;


use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;

class BaseAttribute
{
    protected $title;
    protected $type;
    protected $config;
    protected $value;
    protected $name;
    protected $defaultValue;
    protected $description;
    /**
     * @var bool
     */
    protected $required = false;
    /**
     * @var bool
     */
    protected $system = false;

    public function __construct($params = null)
    {
        if (!empty($params)) {
            if (is_object($params) && $params instanceof BaseAttribute) {
                $this->title        = $params->getTitle();
                $this->name         = $params->getName();
                $this->config       = $params->getConfig();
                $this->value        = $params->getValue();
                $this->required     = $params->isRequired();
                $this->system       = $params->isSystem();
                $this->defaultValue = $params->getDefaultValue();
                $this->description  = $params->getDescription();
            } else {
                $this->title        = empty($params['title']) ? null : $params['title'];
                $this->name         = empty($params['name']) ? null : $params['name'];
                $this->value        = empty($params['value']) ? null : $params['value'];
                $this->system       = empty($params['system']) ? false : $params['system'];
                $this->required     = empty($params['required']) ? false : $params['required'];
                $this->defaultValue = empty($params['defaultValue']) ? false : $params['defaultValue'];
                $this->description  = empty($params['description']) ? false : $params['description'];
                $this->config       = empty($params['config']) ? false : $params['config'];
            }
            if (empty($this->title)) $this->title = $this->name;
            if (empty($this->value)) $this->value = $this->defaultValue;
        }

//        if ($this->type && !$this->name) {
//            throw new \BadMethodCallException('You can not create ' . $this->type . ' attribute without name');
//        }
    }

    public function getViewData()
    {
        return [$this->name => $this->value];
    }

    public function getFormWidgetInfo()
    {
        return [
            $this->name => [
                'name'     => $this->name,
                'type'     => $this->type,
                'required' => $this->required,
                'description' => $this->description ?: false,
                'options'  => [

                ],
            ]];
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getWidgetView()
    {
        return $this->type . '.html.twig';
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     * @return BaseAttribute
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param bool $required
     * @return BaseAttribute
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSystem()
    {
        return $this->system;
    }

    /**
     * @param boolean $system
     */
    public function setSystem($system)
    {
        $this->system = $system;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return BaseAttribute
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}