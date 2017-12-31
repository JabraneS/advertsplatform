<?php

namespace JOMANEL\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;


class ContactType extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options){

        //
        if($options['locale'] == "fr"){
          $name               = "Votre nom";
          $name_blank_msg     = "Saisissez votre nom s'il vous plaît";
          
          $email              = "Votre adresse email";
          $email_blank_msg    = "Saisissez une adresse email valide s'il vous plaît";
          $email_notValid_msg = "Votre email ne semble pas être valide";
          
          $subject            = "Sujet";
          $subject_blank_msg  = "Saisissez un sujet s'il vous plaît";
          
          $message            = "Votre message";
          $message_blank_msg  = "Saisissez un message s'il vous plaît";
        }
        else{
          $name               = "Your name";
          $name_blank_msg     = "Please provide your name";
          
          $email              = "Your email address";
          $email_blank_msg    = "Please provide a valid email";
          $email_notValid_msg = "Your email doesn't seems to be valid";
          
          $subject            = "Subject";
          $subject_blank_msg  = "Please give a Subject";
          
          $message            = "Your message here";
          $message_blank_msg  = "Please provide a message here";
          
        }
        //   

        
        $builder->add('name', TextType::class, array('attr'        => array('placeholder' => $name),
                                                     'constraints' => array(new NotBlank(array("message" => $name_blank_msg
                                                                                              )
                                                                                        ),
                                                                           )
                                                    )
                     )//name
                ->add('email', EmailType::class, array('attr'        => array('placeholder' => $email),
                                                       'constraints' => array(new NotBlank(array("message" => $email_blank_msg
                                                                                                )
                                                                                          ),
                                                                              new Email(array("message" => $email_notValid_msg
                                                                                             )
                                                                                       ),
                                                                             )
                                                      )
                     )//email
                ->add('subject', TextType::class, array('attr'        => array('placeholder' => $subject),
                                                        'constraints' => array(new NotBlank(array("message" => 
                                                                                                            $subject_blank_msg
                                                                                                 )
                                                                                            ),
                                                                               )
                                                        )
                     )//subject
                
                ->add('message', TextareaType::class, array('attr'        => array('placeholder' => $message),
                                                            'constraints' => array(new NotBlank(array("message" => 
                                                                                                       $message_blank_msg
                                                                                                      )
                                                                                               ),
                                                                                  )
                                                           )
                     )//message
        ;//$builder

    }//fnc

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'JOMANEL\CoreBundle\Entity\Contact'));

        $resolver->setRequired(array('locale'));

    }//fnc

    /*public function setDefaultOptions(OptionsResolver $resolver){
    
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }//fnc

    public function getName(){
        
        return 'contact_form';
    }//fnc*/


}//class