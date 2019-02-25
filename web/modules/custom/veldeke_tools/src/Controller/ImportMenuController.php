<?php

namespace Drupal\veldeke_tools\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\menu_link_content\Entity\MenuLinkContent;

/**
 * Class ImportMenuController.
 */
class ImportMenuController extends ControllerBase
{

  /**
   * Import_menu.
   *
   * @return string
   *   Return Hello string.
   */
  public function import_menu()
  {

    $uri = 'http://vtv6.dvmwk.kem/boe_tools/menu-structure.json';
    try {
      $response = \Drupal::httpClient()
        ->get($uri, array('headers' => array('Accept' => 'text/plain')));
      $data = (string)$response->getBody();
      $data = json_decode($data);

      if (empty($data)) {
        return FALSE;
      } else {

        // Loads current main menu for checking already exisiting items.
        $current_items = [];
        $tree = \Drupal::menuTree()->load('main', new \Drupal\Core\Menu\MenuTreeParameters());
        foreach ($tree as $item) {
          $current_items[] = $item->link->getTitle();
        }

        foreach ($data as $datum) {

          if (in_array($datum->link->link_title, $current_items) == false) {
            drupal_set_message("{$datum->link->link_title} not yet in");

            $menu_link = MenuLinkContent::create([
              'title' => $datum->link->link_title,
              'link' => ['uri' => 'internal:/' . $datum->link->link_path],
              'menu_name' => 'main',
              'expanded' => TRUE,
            ]);

            $menu_link->save();
          } else {
            drupal_set_message("{$datum->link->link_title} already in");
          }
        }
      }
    } catch (RequestException $e) {
      echo $e;
      return FALSE;
    }

    return [
      '#type' => 'markup',
      '#markup' => $this->t('importing menu')
    ];
  }

}
