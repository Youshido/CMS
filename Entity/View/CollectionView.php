<?php

namespace Youshido\CMSBundle\Entity\View;

use Doctrine\ORM\Mapping as ORM;
use Youshido\CMSBundle\Service\AttributeService;
use Youshido\CMSBundle\Service\StructureService;
use Youshido\CMSBundle\Structure\Attribute\AttributedTrait;
use Youshido\CMSBundle\Structure\Attribute\TextAttribute;

/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class CollectionView extends View
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttributes([
            new TextAttribute(["name" => "template"]),
            new TextAttribute(["name" => "contentType"]),
            new TextAttribute(["name" => "maxCount"]),
        ]);
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->applyAttributesToObject();
    }

    public function getAvailableSubviews(StructureService $service)
    {
        $attr = $this->getAttributesViewValues();
        if (empty($attr['contentType'])) {
            return [];
        }

        $contentType = $service->getContentTypeInstanceByName($attr['contentType']);
        return [
            [
                'id'   => $contentType->getId(),
                'name' => $contentType->getName(),
                'objectTitle' => $contentType->getTitle(),
                'type' => 'content_type'
            ]
        ];
    }

    public function getType()
    {
        return self::TYPE_COLLECTION_VIEW;
    }

}
