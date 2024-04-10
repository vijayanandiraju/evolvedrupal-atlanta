<?php

declare(strict_types=1);

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Returns responses for Hello World routes.
 */
final class HelloController extends ControllerBase {

  public function hello($name = NULL): array {
    $output = [
      '#type' => 'item',
      '#markup' => $this->t('Hello World!'),
    ];

    if ($name) {
      $output['#markup'] = $this->t('Hello @person!', ['@person' => $name]);
    }

    return $output;
  }

  public function nodeCheck($name, $id): array {
    $output['greeting'] = [
      '#type' => 'item',
      '#markup' => $this->t('Hello @person!', ['@person' => $name]),
    ];

    if ($node = Node::load($id)) {
      $output['node'] = [
        '#type' => 'item',
        '#markup' => $node->toLink(),
      ];
    }

    return $output;
  }

}
