<?php

namespace Youshido\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Youshido\CMSBundle\Service\AttributeService;

class AttributeTypeForm extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            //->add('title')
            ->add('name')
            ->add('type', 'choice', [
                'choices' => AttributeService::getAvailableTypes(),
            ])
            ->add('system', 'checkbox')
            ->add('default_value', 'text')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Youshido\CMSBundle\Structure\Attribute\BaseAttribute',
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'cmsbundle_attribute_type';
    }
}
