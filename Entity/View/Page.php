<?php

namespace Youshido\CMSBundle\Entity\View;

use Doctrine\ORM\Mapping as ORM;
use Youshido\CMSBundle\Structure\Attribute\ImageAttribute;
use Youshido\CMSBundle\Structure\Attribute\TextareaAttribute;
use Youshido\CMSBundle\Structure\Attribute\TextAttribute;

/**
 * Page
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
 * @property $title
 * @property $alias
 * @property $template
 * @property $description
 *
 */
class Page extends View
{

    public function __construct()
    {
        parent::__construct();
        $this->setPosition(0);
        $this->setAttributes([
            new TextAttribute(["name" => "title"]),
            new TextAttribute(["name" => "alias"]),
            new TextAttribute(["name" => "template"]),
            new TextareaAttribute(["name" => "description"]),
            new ImageAttribute(["name" => "background"]),
        ]);
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->applyAttributesToObject();
    }

    public function getAllowedSubviews()
    {
        return [
            'ContentView', 'CollectionView', 'CustomView'
        ];
    }
}
