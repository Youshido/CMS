<?php
/*
 * This file is a part of cms project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 11:10 PM 6/28/15
 */

namespace Youshido\CMSBundle\Entity\View;

use Doctrine\ORM\Mapping as ORM;
use Youshido\CMSBundle\Structure\Attribute\AttributedInterface;
use Youshido\CMSBundle\Structure\Attribute\AttributedTrait;


/**
 * @ORM\Entity
 * @ORM\Table(name="content_type")
 * @ORM\HasLifecycleCallbacks
 */
class ContentType implements AttributedInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    use AttributedTrait {
        AttributedTrait::__construct as private _attributedConstruct;
    }

    public function __construct()
    {
        $this->_attributedConstruct();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return ContentType
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getObjectTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return ContentType
     */
    public function setObjectTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return ContentType
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getType()
    {
        return View::TYPE_CONTENT_VIEW;
    }

}