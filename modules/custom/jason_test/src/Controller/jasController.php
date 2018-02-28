<?php

namespace Drupal\jason_test\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\jason_test\Entity\jasInterface;

/**
 * Class jasController.
 *
 *  Returns responses for Jas routes.
 */
class jasController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Jas  revision.
   *
   * @param int $jas_revision
   *   The Jas  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($jas_revision) {
    $jas = $this->entityManager()->getStorage('jas')->loadRevision($jas_revision);
    $view_builder = $this->entityManager()->getViewBuilder('jas');

    return $view_builder->view($jas);
  }

  /**
   * Page title callback for a Jas  revision.
   *
   * @param int $jas_revision
   *   The Jas  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($jas_revision) {
    $jas = $this->entityManager()->getStorage('jas')->loadRevision($jas_revision);
    return $this->t('Revision of %title from %date', ['%title' => $jas->label(), '%date' => format_date($jas->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Jas .
   *
   * @param \Drupal\jason_test\Entity\jasInterface $jas
   *   A Jas  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(jasInterface $jas) {
    $account = $this->currentUser();
    $langcode = $jas->language()->getId();
    $langname = $jas->language()->getName();
    $languages = $jas->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $jas_storage = $this->entityManager()->getStorage('jas');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $jas->label()]) : $this->t('Revisions for %title', ['%title' => $jas->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all jas revisions") || $account->hasPermission('administer jas entities')));
    $delete_permission = (($account->hasPermission("delete all jas revisions") || $account->hasPermission('administer jas entities')));

    $rows = [];

    $vids = $jas_storage->revisionIds($jas);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\jason_test\jasInterface $revision */
      $revision = $jas_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $jas->getRevisionId()) {
          $link = $this->l($date, new Url('entity.jas.revision', ['jas' => $jas->id(), 'jas_revision' => $vid]));
        }
        else {
          $link = $jas->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.jas.translation_revert', ['jas' => $jas->id(), 'jas_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.jas.revision_revert', ['jas' => $jas->id(), 'jas_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.jas.revision_delete', ['jas' => $jas->id(), 'jas_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['jas_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
