<?php

namespace Drupal\veldeke_import\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Row;

/**
 * Source plugin to import data from JSON files
 * @MigrateSource(
 *   id = "books"
 * )
 */
class Books extends SourcePluginBase {

  public function prepareRow(Row $row) {
    $name = $row->getSourceProperty('name');
    // make sure the title isn't too long for Drupal
    if (strlen($name) > 255) {
      $row->setSourceProperty('name', substr($name, 0, 255));
    }
    return parent::prepareRow($row);
  }

  public function getIds() {
    $ids = [
      'id' => [
        'type' => 'string'
      ]
    ];
    return $ids;
  }

  public function fields() {
    return array(
//      'date' => $this->t('Date'),
//      'league' => $this->t('League'),
//      'country_code' => $this->t('Country code'),
//      'key' => $this->t('Key'),
//      'id' => $this->t('ID'),
      'title' => $this->t('Title'),
//      'code' => $this->t('Code'),
////      'date' => $this->t('Date Published'),
//      'json_filename' => $this->t("Source JSON filename")
    );
  }

  public function __toString() {
    return "json data";
  }

  /**
   * Initializes the iterator with the source data.
   * @return \Iterator
   *   An iterator containing the data for this source.
   */
  protected function initializeIterator() {

    $filepath = "http://veldeke7.test/veldeke_export/books.json";
    $books = json_decode(file_get_contents($filepath), TRUE); // sets the title, body, etc.


    foreach ($books['content'] as $book) {

      $item = $book['item'];

      $row['title'] = $item['title'];
      $row['id'] = $item['nid'];
      $row['body']=$item['body'];



      // migrate needs the date as a UNIX timestamp
//        try {
//          // put your source data's time zone here, or just use strtotime() if it's already in UTC
//          $d = new \DateTime($date, new \DateTimeZone('America/Los_Angeles'));
//          $row['date'] = $d->format('U');
//        } catch (\Exception $e) {
//          echo "Exception: " . $e->getMessage() . "\n";
//          $row['date'] = time();  // fallback – set it to now so we don't have errors
//        }

      // append it to the array of rows we can import
      $rows[] = $row;
    }

//    print_r($rows);

    // Migrate needs an Iterator class, not just an array
    return new \ArrayIterator($rows);
  }
}