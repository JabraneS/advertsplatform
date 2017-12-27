<?php


namespace JOMANEL\PlatformBundle\Form;

use JOMANEL\PlatformBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
//use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\HttpFoundation\Request;

class AdvertType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    //
    if($options['locale'] == "fr"){
      $formatDate = 'dd/MM/yyyy';
      $year       = 'Année';
      $month      = 'Mois';
      $day        = 'Jour';
      $hour       = 'Heure';
      $minute     = 'Minute';
    }
    else{
      $formatDate = 'MM/dd/yyyy';
      $year       = 'Year';
      $month      = 'Month';
      $day        = 'Day';
      $hour       = 'Hour';
      $minute     = 'Minute';
    }
    //    
    $builder->add('date',DateTimeType::class, array('date_format' => $formatDate,'placeholder' => array('year' => $year,
                                                                                                        'month' => $month,
                                                                                                        'day' => $day,
                                                                                                        'hour' => $hour, 
                                                                                                        'minute' => $minute 
                                                                                                        )
                                                   )
                 )

            ->add('title_fr',  TextType::class, ['label_format' => '%name%'])
            ->add('title_en',      TextType::class, ['label_format' => '%name%'])
      
            ->add('author',     TextType::class, ['label_format' => '%name%'])

            ->add('email',      EmailType::class)

            ->add('contenu_fr',    TextareaType::class, ['label_format' => '%name%'])
            ->add('contenu_en',    TextareaType::class, ['label_format' => '%name%'])

            ->add('image',      ImageType::class)

            ->add('categories', EntityType::class, array( 'label_format'  => '%name%',
                                                          'class'         => 'JOMANELPlatformBundle:Category',
                                                          'choice_label'  => 'name_'.$options['locale'],
                                                          'multiple'      => true,
                                                          'query_builder' => function(CategoryRepository $repository)  {
                                                             
                                                                                return $repository->sortAlphabeticallyQueryBuilder();
                                                                              }
                                                        )
                 )

            ->add('save',      SubmitType::class, ['label_format' => '%name%'])
    ;//build->

    $builder->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event) {
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
                              )
    ;//build->

  }//fnc 

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'JOMANEL\PlatformBundle\Entity\Advert'
    ));

    $resolver->setRequired(array(
      'locale'
    ));

  }//fnc

}//class

