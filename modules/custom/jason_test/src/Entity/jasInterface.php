<?php

namespace Drupal\jason_test\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Jas entities.
 *
 * @ingroup jason_test
 */
interface jasInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Jas name.
   *
   * @return string
   *   Name of the Jas.
   */
  public function getName();

  /**
   * Sets the Jas name.
   *
   * @param string $name
   *   The Jas name.
   *
   * @return \Drupal\jason_test\Entity\jasInterface
   *   The called Jas entity.
   */
  public function setName($name);

  /**
   * Gets the Jas creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Jas.
   */
  public function getCreatedTime();

  /**
   * Sets the Jas creation timestamp.
   *
   * @param int $timestamp
   *   The Jas creation timestamp.
   *
   * @return \Drupal\jason_test\Entity\jasInterface
   *   The called Jas entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Jas published status indicator.
   *
   * Unpublished Jas are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Jas is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Jas.
   *
   * @param bool $published
   *   TRUE to set this Jas to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\jason_test\Entity\jasInterface
   *   The called Jas entity.
   */
  public function setPublished($published);

  /**
   * Gets the Jas revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Jas revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\jason_test\Entity\jasInterface
   *   The called Jas entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Jas revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Jas revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\jason_test\Entity\jasInterface
   *   The called Jas entity.
   */
  public function setRevisionUserId($uid);

}
