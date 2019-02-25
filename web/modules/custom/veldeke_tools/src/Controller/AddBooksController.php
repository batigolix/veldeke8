<?php

namespace Drupal\veldeke_tools\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class AddBooksController.
 */
class AddBooksController extends ControllerBase {

  /**
   * Build.
   *
   * @return string
   *   Return Hello string.
   */
  public function build() {

//kint('test');test

    // @todo clean url.
    $uri = 'http://veldeke7.drupalvm.test/veldeke_export/book-structure.json';
    try {
      $response = \Drupal::httpClient()
          ->get($uri, array('headers' => array('Accept' => 'text/plain')));
      $data = (string) $response->getBody();
      $data = json_decode($data);


      if (empty($data)) {
        return FALSE;
      }
      else {
        foreach ($data as $datum) {

//          kint($datum);

          $nid = _veldeke_tools_migrate_get_local_nid($datum->nid);

          kint($datum->pid);

          $pid = 0;
          if (is_int($datum->pid) && $datum->pid > 0) {
            $pid = _veldeke_tools_migrate_get_local_nid($datum->pid);
          }
          $bid = _veldeke_tools_migrate_get_local_nid($datum->bid);

          kint($pid);

          if ($nid && $bid && $pid >= 0) {
            $query = \Drupal::database()->upsert('book');
            $query->fields([
                'nid',
                'bid',
                'pid',
                'has_children',
                'weight',
                'depth',
                'p1',
                'p2',
                'p3',
                'p4',
                'p5',
                'p6',
                'p7',
                'p8',
                'p9',
            ]);
            $query->values([
                $nid,
                $bid,
                $pid,
                $datum->has_children,
                $datum->weight,
                $datum->depth,
                $datum->p1 > 0 ? _veldeke_tools_migrate_get_local_nid($datum->p1) : 0,
                $datum->p2 > 0 ? _veldeke_tools_migrate_get_local_nid($datum->p2) : 0,
                $datum->p3 > 0 ? _veldeke_tools_migrate_get_local_nid($datum->p3) : 0,
                $datum->p4 > 0 ? _veldeke_tools_migrate_get_local_nid($datum->p4) : 0,
                $datum->p5 > 0 ? _veldeke_tools_migrate_get_local_nid($datum->p5) : 0,
                $datum->p6 > 0 ? _veldeke_tools_migrate_get_local_nid($datum->p6) : 0,
                $datum->p7 > 0 ? _veldeke_tools_migrate_get_local_nid($datum->p7) : 0,
                $datum->p8 > 0 ? _veldeke_tools_migrate_get_local_nid($datum->p8) : 0,
                $datum->p9 > 0 ? _veldeke_tools_migrate_get_local_nid($datum->p9) : 0,
            ]);
            $query->key('nid');
            $query->execute();
          }
        }
      }
    }
    catch (RequestException $e) {
      echo $e;
      return FALSE;
    }
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: build')
    ];
  }

}
