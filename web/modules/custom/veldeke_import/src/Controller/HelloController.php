<?php

namespace Drupal\veldeke_import\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class HelloController.
 */
class HelloController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello($name) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: hello with parameter(s): $name'),
    ];
  }

}
