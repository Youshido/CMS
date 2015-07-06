<?php

namespace Youshido\CMSBundle\Service\Provider;
use Symfony\Component\DependencyInjection\ContainerAware;
use Youshido\CMSBundle\Structure\Attribute\BaseAttribute;

/**
 * Date: 03.07.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */
class PageProvider extends ContainerAware
{

    public function findPageByAlias($alias)
    {
        $pages = $this->container->get('doctrine.orm.entity_manager')->getRepository('YCMSBundle:View\Page')
            ->findAll();

        foreach($pages as $page){
            if(isset($page->alias) && $page->alias == $alias){
                return $page;
            }
        }

        return null;
    }

}