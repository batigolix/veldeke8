<?php

namespace Drupal\veldeke_import\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateException;

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
      'nid' => $this->t('NID'),
      'title' => $this->t('Title'),
      'body' => $this->t('Body'),
      'changed' => $this->t('Last changed'),
      'created' => $this->t('Created'),
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
    try {
      $result = file_get_contents($filepath);
    } catch (\Exception $e) {
      echo "Exception: " . $e->getMessage() . "\n";
      $row['date'] = time();  // fallback – set it to now so we don't have errors
      throw new MigrateException('Cannot read from source');
    }
    if ($result == FALSE) {
      throw new MigrateException('Cannot download remote file  by SFTP.');
    }
    $books = json_decode($result, TRUE); // sets the title, body, etc.
    foreach ($books['content'] as $book) {
      $item = $book['item'];
      $row['title'] = $item['title'];
      $row['id'] = $item['nid'];
      $row['body'] = $item['body'];
      $row['created'] = $item['created'];
      $row['changed'] = $item['changed'];
      $rows[] = $row;
    }
    return new \ArrayIterator($rows);
  }
}