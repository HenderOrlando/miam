<?php

namespace Bundle\MiamBundle\Form;

use Symfony\Components\Form\Form;
use Symfony\Components\Form\FieldGroup;
use Symfony\Components\Form\ChoiceField;
use Symfony\Components\Form\TextField;
use Symfony\Components\Form\CheckboxField;
use Symfony\Components\Form\NumberField;
use Symfony\Components\Form\PasswordField;
use Symfony\Components\Form\DoubleTextField;
use Symfony\Components\Validator\Validator;
use Symfony\Components\Validator\ConstraintValidatorFactory;
use Symfony\Components\Validator\Mapping\ClassMetadataFactory;
use Symfony\Components\Validator\Mapping\ClassMetadata;
use Symfony\Components\Validator\Mapping\Loader\LoaderChain;
use Symfony\Components\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Components\Validator\Mapping\Loader\XmlFileLoader;
use Symfony\Components\Validator\MessageInterpolator\XliffMessageInterpolator;
use Symfony\Foundation\UniversalClassLoader;

use Bundle\MiamBundle\Entities\Project;

/**
 * test project form
 */
class ProjectForm extends Form
{
  public function __construct($object, array $options = array())
  {
    $this->addOption('message_file');
    $this->addOption('validation_file');
    parent::__construct('project', $object, $this->createValidator($options['message_file'], $options['validation_file']), $options);

    $this->add(new TextField('name'));
    $this->add(new TextField('color'));
  } 

  public function createValidator($messageFile, $validationFile)
  {
    $metadataFactory = new ClassMetadataFactory(new LoaderChain(array(
      new StaticMethodLoader('loadValidatorMetadata'),
      new XmlFileLoader($validationFile)
    )));
    $validatorFactory = new ConstraintValidatorFactory();
    $messageInterpolator = new XliffMessageInterpolator($messageFile);
    $validator = new Validator($metadataFactory, $validatorFactory, $messageInterpolator);

    return $validator;
  }
}
