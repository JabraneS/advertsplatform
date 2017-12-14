<?php


namespace JOMANEL\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    //$builder->remove('date');
  }

  public function getParent()
  {
    return CategoryType::class;
  }
}
