<?php

namespace Catrobat\CatrowebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('views')
            ->add('downloads')
            ->add('filename')
            ->add('thumbnail')
            ->add('screenshot')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catrobat\CoreBundle\Entity\Project'
        ));
    }

    public function getName()
    {
        return 'catrobat_catrowebbundle_projecttype';
    }
}
