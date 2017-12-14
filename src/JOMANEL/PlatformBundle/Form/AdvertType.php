<?php


namespace JOMANEL\PlatformBundle\Form;

use JOMANEL\PlatformBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
class AdvertType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

    $builder
      ->add('date',       DateTimeType::class)
      ->add('titre',      TextType::class)
      ->add('title',      TextType::class)
      //
      //->add('firstname', null, ['label' => 'register.labels.firstname'])

      ->add('author',     TextType::class, ['label_format' => '%name%'])//'label'=> 'Submit me', pour le nom (a mettre ds cat)
      //
      ->add('email',      EmailType::class)
      ->add('contenu',    TextareaType::class)
      ->add('content',    TextareaType::class)
      ->add('image',      ImageType::class)
      ->add('categories', EntityType::class, array(
        'label_format'  => '%name%',
        'class'         => 'JOMANELPlatformBundle:Category',
        'choice_label'  => 'name',
        'multiple'      => true,
        'query_builder' => function(CategoryRepository $repository)  {

          $request = new Request(); 
          $locale = $request->getLocale();
          
          return $repository->sortAlphabeticallyQueryBuilder();
        }
      ))
      ->add('save',      SubmitType::class, ['label_format' => '%name%'])
    ;

    $builder->addEventListener(
      FormEvents::PRE_SET_DATA,
      function(FormEvent $event) {
        $advert = $event->getData();

        if (null === $advert) {
          return;
        }

        if (!$advert->getPublished() || null === $advert->getId()) {
          $event->getForm()->add('published', CheckboxType::class, ['label_format' => '%name%'], array('required' => false));
        } else {
          $event->getForm()->remove('published');
        }
      }
    );
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'JOMANEL\PlatformBundle\Entity\Advert'
    ));
  }
}//class

