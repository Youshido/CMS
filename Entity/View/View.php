<?php
/**
 * Created by PhpStorm.
 * User: mounter
 * Date: 6/25/15
 * Time: 4:11 PM
 */

namespace Youshido\CMSBundle\Entity\View;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
//use Gedmo\Mapping\Annotation as Gedmo;

use Youshido\CMSBundle\Structure\Attribute\AttributedInterface;
use Youshido\CMSBundle\Structure\Attribute\AttributedTrait;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"view" = "View", "page" = "Page", "collection_view" = "CollectionView", "content_view" = "ContentView" })
 */
class View implements AttributedInterface
{

    const TYPE_CONTENT_TYPE = 'content_type';
    const TYPE_CONTENT_VIEW = 'content_view';
    const TYPE_COLLECTION_VIEW = 'collection_view';
    const TYPE_PAGE = 'page';

    use AttributedTrait {
        AttributedTrait::__construct as private _attributedConstruct;
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="_title", type="string", length=255, nullable=true)
     */
    private $objectTitle;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_visible", type="boolean")
     */
    private $isVisible;

    /**
     * @ORM\OneToMany(targetEntity="View", mappedBy="parent")
     * @ORM\OrderBy({"position" = "ASC"})
     **/
    private $subviews;

    /**
     * @ORM\ManyToOne(targetEntity="View", inversedBy="subviews", cascade="persist")
     * @ORM\JoinColumn(name="id_parent", referencedColumnName="id", onDelete="SET NULL")
     **/
    private $parent;
    /**
     * @ORM\ManyToOne(targetEntity="View")
     * @ORM\JoinColumn(name="source_view_id", referencedColumnName="id", onDelete="SET NULL")
     **/
    private $sourceView;

    /**
     * @ORM\ManyToOne(targetEntity="ContentType")
     * @ORM\JoinColumn(name="source_content_type_id", referencedColumnName="id", onDelete="SET NULL")
     **/
    private $sourceContentType;

    /**
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    public function __construct()
    {
        $this->_attributedConstruct();
        $this->subviews = new ArrayCollection();
        $this->isVisible = true;
        $this->position = 0;
    }

    public function isSubviewsAllowed()
    {
        return true;
    }

    public function getSourceTitle()
    {
        return $this->getSourceView() ? $this->getSourceView()->getObjectTitle() : "";
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getObjectTitle()
    {
        return $this->objectTitle;
    }

    /**
     * @param mixed $objectTitle
     */
    public function setObjectTitle($objectTitle)
    {
        $this->objectTitle = $objectTitle;
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
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection
     */
    public function getSubviews()
    {
        return $this->subviews;
    }

    /**
     * @param mixed $subviews
     * @return $this
     */
    public function setSubviews($subviews)
    {
        $this->subviews = new ArrayCollection();

        foreach ($subviews as $view) {
            $this->subviews->add(clone $view);
        }
        return $this;
    }

    public function addSubview($subview)
    {
        $this->subviews->add($subview);
    }

    public function removeSubview($subview)
    {
        $this->subviews->removeElement($subview);
    }

    /**
     * @param $id
     * @return View|null
     */
    public function findSubviewWithId($id)
    {
        foreach($this->subviews as $view) {
            if ($view->getId() == $id) {
                return $view;
            }
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getSourceView()
    {
        return $this->sourceView;
    }

    /**
     * @param mixed $sourceView
     */
    public function setSourceView($sourceView)
    {
        $this->sourceView = $sourceView;
    }

    /**
     * @return mixed
     */
    public function getSourceContentType()
    {
        return $this->sourceContentType;
    }

    /**
     * @param mixed $sourceContentType
     * @return View
     */
    public function setSourceContentType($sourceContentType)
    {
        $this->sourceContentType = $sourceContentType;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        return $this->isVisible;
    }

    /**
     * @param boolean $isVisible
     */
    public function setVisible($isVisible)
    {
        $this->isVisible = $isVisible;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     * @return View
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    public function getType()
    {
        throw new \Exception();
    }

    public function __toString()
    {
        return $this->objectTitle ? $this->objectTitle : "Object";
    }

}