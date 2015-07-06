<?php
/**
 * Created by PhpStorm.
 * User: mounter
 * Date: 6/26/15
 * Time: 2:39 PM
 */

namespace Youshido\CMSBundle\Entity\View;

use Doctrine\ORM\Mapping as ORM;
use Youshido\CMSBundle\Structure\Attribute\HiddenAttribute;
use Youshido\CMSBundle\Structure\Attribute\TextAttribute;


/**
 * @ORM\Entity(repositoryClass="Youshido\CMSBundle\Entity\Repository\ContentViewRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ContentView extends View
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttributes([
            new TextAttribute(["name" => "template"]),
        ]);
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->applyAttributesToObject();
    }

    public function isSubviewsAllowed()
    {
        return false;
    }

    public function getType()
    {
        return self::TYPE_CONTENT_VIEW;
    }

}