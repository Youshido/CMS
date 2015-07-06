<?php
/*
 * This file is a part of jobrain-site project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 9:17 PM 6/20/15
 */

namespace Youshido\CMSBundle\Service;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Youshido\CMSBundle\Structure\Attribute\AttributedInterface;
use Youshido\CMSBundle\Structure\Attribute\BaseAttribute;

class FormHelperService extends ContainerAware {

    /**
     * @param AttributedInterface $object
     * @return Form
     */
    public function getVarsFormForAttributes($object)
    {
        /**
         * @var FormBuilder $formBuilder
         */
        $formBuilder = $this->container->get('form.factory')->createNamedBuilder('form'.$object->getId(), 'form', $object->getAttributesViewValues());
        foreach ($object->getAttributesFormFields() as $field) {
            $this->buildFormItem($field['name'], $field, $formBuilder);
        }
        return $formBuilder->getForm();
    }

    public function buildFormItem($column, $info, FormBuilder $formBuilder)
    {
        $attr = array('class' => '', 'autocomplete' => 'off');

        if ($info['type'] == 'text') {
            $attr['class'] = 'form-control';
        } elseif ($info['type'] == 'entity') {
            $attr['class'] = 'form-control';
            if (!empty($info['multiple'])) {
                $attr['class'] .= ' basic-tags w500';
            }
        }
        if (!empty($info['mask'])) {
            $attr['data-mask'] = $info['mask'];
            $attr['class'] .= ' input-mask';
        }
        if (!empty($info['placeholder'])) {
            $attr['placeholder'] = $info['placeholder'];
        }
        $transformer = new DateTimeToStringTransformer();

        switch ($info['type']) {
            case 'date':
                $formBuilder->add(
                    $formBuilder->create($column, 'text', array('attr' => $attr))->addModelTransformer($transformer)
                );
                break;
            case 'entity':
                $options = array('class' => $info['entity'], 'required' => false, 'placeholder' => 'Any ' . $info['title'], 'label' => $info['title'], 'attr' => $attr);
                if (!empty($info['where'])) {
                    $options['query_builder'] = function (EntityRepository $er) use ($info) {
                        return $er->createQueryBuilder('m')
                            ->where($info['where']);
                    };
                }
                if (!empty($info['required'])) {
                    unset($options['placeholder']);
                }
                if (!empty($info['groupBy'])) {
                    $options['group_by'] = $info['groupBy'];
                }
                if (!empty($info['multiple'])) {
                    $options['multiple'] = true;
                }
                $formBuilder->add($column, 'entity', $options);
                break;
            case 'collection':
                $formBuilder->add($column, 'collection', array(
                    'type' => new $info['form'](),
                    'allow_add' => true,
                    'allow_delete' => true,
                ));
                break;
            case 'textarea':
                $formBuilder->add($column, 'textarea', array('attr' => $attr));
                break;
            case 'boolean':
                $formBuilder->add($column, 'checkbox', array('attr' => $attr));
                break;
            case 'file':
                $formBuilder->add($column, 'youshido_file', array(
                    'attr' => $attr,
                    'entity_class' => 'Youshido\\Bundle\\CMSBundle\\Structure\\Attribute\\FileAttribute',
                    'entity_property' => 'value'
                ));
                break;
            case 'image':
                $formBuilder->add($column, 'youshido_file', array(
                    'attr' => $attr,
                    'required' => false,
                    'entity_class' => 'Youshido\\Bundle\\CMSBundle\\Structure\\Attribute\\ImageAttribute',
                    'entity_property' => 'value'
                ));
                break;
            case 'label':
                $formBuilder->add($column, 'hidden', array('attr' => $attr));
                break;
            case 'hidden':
                $formBuilder->add($column, 'hidden', array('attr' => $attr));
                break;
            default:
                $formBuilder->add($column, 'text', array('attr' => $attr));

        }
    }


    public function get($service) {
        return $this->container->get($service);
    }

}