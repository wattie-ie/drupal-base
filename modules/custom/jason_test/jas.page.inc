<?php

/**
 * @file
 * Contains jas.page.inc.
 *
 * Page callback for Jas entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Jas templates.
 *
 * Default template: jas.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_jas(array &$variables) {
  // Fetch jas Entity Object.
  $jas = $variables['elements']['#jas'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
