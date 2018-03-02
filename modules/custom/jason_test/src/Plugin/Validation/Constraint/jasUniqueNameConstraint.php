<?php

namespace Drupal\jason_test\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that the submitted name/title is a unique value
 *
 * @Constraint(
 *   id = "unique_title",
 *   label = @Translation("Unique Title", context = "Validation"),
 * )
 */
class jasUniqueNameConstraint extends Constraint
{

  // The message that will be shown if the value is not unique
  public $notUnique = '%value is not unique';
}