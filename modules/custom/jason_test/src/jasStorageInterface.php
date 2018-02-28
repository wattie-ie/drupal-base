<?php

namespace Drupal\jason_test;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface jasStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Jas revision IDs for a specific Jas.
   *
   * @param \Drupal\jason_test\Entity\jasInterface $entity
   *   The Jas entity.
   *
   * @return int[]
   *   Jas revision IDs (in ascending order).
   */
  public function revisionIds(jasInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Jas author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Jas revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\jason_test\Entity\jasInterface $entity
   *   The Jas entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(jasInterface $entity);

  /**
   * Unsets the language for all Jas with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
