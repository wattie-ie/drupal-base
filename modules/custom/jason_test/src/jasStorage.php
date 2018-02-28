<?php

namespace Drupal\jason_test;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\jason_test\Entity\jasInterface;

/**
 * Defines the storage handler class for Jas entities.
 *
 * This extends the base storage class, adding required special handling for
 * Jas entities.
 *
 * @ingroup jason_test
 */
class jasStorage extends SqlContentEntityStorage implements jasStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(jasInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {jas_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {jas_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(jasInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {jas_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('jas_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
