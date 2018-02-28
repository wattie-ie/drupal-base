<?php

namespace Drupal\jason_test;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Jas entity.
 *
 * @see \Drupal\jason_test\Entity\jas.
 */
class jasAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\jason_test\Entity\jasInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished jas entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published jas entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit jas entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete jas entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add jas entities');
  }

}
