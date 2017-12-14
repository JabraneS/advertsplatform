<?php
namespace JOMANEL\PlatformBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsFormsType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostType extends AbstractType
{
 
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', TextType::class)
            
            // here we can override bundle configuration from config.yml
            
            ->add('translations', TranslationsType::class, [
            	'locales' => ['en', 'pl', 'fr', 'es', 'de'],
                // fields that we want to translate
                'fields' => [
                    'title' => [
                        'field_type' => TextType::class,
                        // here we can add standard field options like label, constraints, etc and locale options
                        'constraints'   => new NotBlank
                    ],
                    'content' => [
                        'constraints'   => new NotBlank
                    ]
                ]
            ]);
    }
 
    // ...
 
}