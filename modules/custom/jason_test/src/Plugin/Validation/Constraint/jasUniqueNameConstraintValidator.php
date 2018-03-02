<?php

namespace Drupal\jason_test\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the UniqueTitle constraint.
 */
class jasUniqueNameConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    foreach ($items as $item) {
      // Next check if the value is unique.
      if (!$this->isUnique($item->value)) {
        $this->context->addViolation($constraint->notUnique, ['%value' => $item->value]);
      }
    }
  }

  private function isUnique($value) {
    // Check if the title is unique.
    //$bundle = $node->bundle();
    // or $bundle='my_bundle_type';
    $query = \Drupal::entityQuery('jas')
        ->condition('status', 1)
        //->condition('type', 'jason_test')
        ->condition('name', $value, 'CONTAINS');
    $entity_ids = $query->execute();
    return (empty($entity_ids) ? true:false);
  }

}