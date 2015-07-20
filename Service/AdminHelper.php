<?php
/**
 * Date: 20.07.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\CMSBundle\Service;


use Symfony\Component\DependencyInjection\ContainerAware;
use Youshido\CMSBundle\Entity\View\CollectionView;
use Youshido\CMSBundle\Entity\View\View;

class AdminHelper extends ContainerAware
{

    public function getBackUrl()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        $adminContext = $this->container->get('adminContext');
        $module = $adminContext->getActiveModule();

        if ($id = $request->get('id', false)) {
            /** @var View $object */
            $object = $this->container->get('doctrine')->getRepository($module['entity'])
                ->find($id);

            if ($object) {
                if ($object->getParent()) {

                    switch (true) {
                        case $object->getParent() instanceof CollectionView:
                            return $this->container->get('router')->generate('admin.dictionary.edit', [
                                'module' => 'cms-view-constructor',
                                'id' => $object->getParent()->getId()
                            ]);

                        default:
                            return $this->container->get('router')->generate('admin.dictionary.edit', [
                                'module' => 'cms-pages-constructor',
                                'id' => $object->getParent()->getId()
                            ]);
                    }
                }
            }
        }

        return false;
    }

}