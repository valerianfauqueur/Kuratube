<?php

namespace AppBundle\Form;

use AppBundle\AppBundle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title', 'text', array(
                    'label' => false,
                    'attr' => array('class' => 'fill__title', 'placeholder' => 'Give your post a title'),
                ))
                ->add('video', 'text', array('attr' => array('placeholder' => 'Enter a Youtube link', 'class' => 'fill__url'), 'label' => false,))
                ->add('content', TextareaType::class, array(
                    'label' => false,
                    'attr' => array('class' => 'fill__description', 'placeholder' => 'Tell us more about this video!'),
                ))
                ->add('channel', 'text', array(
                    'label' => false,
                    'attr' => array('class' => 'fill__url', 'placeholder' => 'Add the youtube channel'),
                ))
                ->add('category', EntityType::class, array(
                    'class' => 'AppBundle:Category',
                    'choice_label' => 'name',
                ))
                ->add('tags', 'entity', array(
                    'class'     => 'AppBundle:Tag',
                    'multiple'  => true,
                    'label' => 'Add some tags to your post',
                ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }


}
