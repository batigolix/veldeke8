<?php

namespace Drupal\veldeke_import\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateException;

/**
 * Source plugin to import data from JSON files.
 *
 * @MigrateSource(
 *   id = "books"
 * )
 */
class Books extends SourcePluginBase {

  /**
   * Tinkers rows from the source.
   */
  public function prepareRow(Row $row) {
    $body = $row->getSourceProperty('body');
    $tags_to_strip = ["h4", "em", "strong"];
    $replace_with = '';
    foreach ($tags_to_strip as $tag) {
      $body = preg_replace("/<\\/?" . $tag . "(.|\\s)*?>/", $replace_with, $body);
    }
    $row->setSourceProperty('body', $body);
    return parent::prepareRow($row);
  }

  /**
   * Fetches the ID.
   */
  public function getIds() {
    $ids = [
      'id' => [
        'type' => 'string',
      ],
    ];
    return $ids;
  }

  /**
   * Provides the fields.
   */
  public function fields() {
    return [
      'nid' => $this->t('NID'),
      'title' => $this->t('Title'),
      'body' => $this->t('Body'),
      'changed' => $this->t('Last changed'),
      'created' => $this->t('Created'),
    ];
  }

  /**
   * Provides string.
   */
  public function __toString() {
    return "json data";
  }

  /**
   * Initializes the iterator with the source data.
   *
   * @return \Iterator
   *   An iterator containing the data for this source.
   */
  protected function initializeIterator() {
    $filepath = "http://veldeke7.test/veldeke_export/books.json";
    try {
      $result = file_get_contents($filepath);
    }
    catch (\Exception $e) {
      echo "Exception: " . $e->getMessage() . "\n";
      // Fallback – set it to now so we don't have errors.
      $row['date'] = time();
      throw new MigrateException('Cannot read from source');
    }
    if ($result == FALSE) {
      throw new MigrateException('Cannot download remote file  by SFTP.');
    }
    // Sets the title, body, etc.
    $books = json_decode($result, TRUE);
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
