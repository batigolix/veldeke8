<?php

namespace Drupal\veldeke_tools\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'ContactBlock' block.
 *
 * @Block(
 *  id = "contact_block",
 *  admin_label = @Translation("Contact block"),
 * )
 */
class ContactBlock extends BlockBase {

  protected $default_markup = 'Dialekvereiniging Veldeke Venlo -&nbsp;Aquamarijn 16, 5912 SV Venlo - Telefoon 077- 3544669 -<a href="/contact"> Contact</a>';

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'label' => t("Contact"),
      'contact_markup_string' => $this->default_markup,
      'cache' => array(
        'max_age' => 3600,
        'contexts' => array(
          'cache_context.user.roles',
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['contact_markup_string_text'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Contact markup'),
      '#description' => $this->t('This text will appear in the example block.'),
      '#default_value' => $this->configuration['contact_markup_string'],
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['contact_markup_string']
      = $form_state->getValue('contact_markup_string_text');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#type' => 'markup',
      '#markup' => $this->configuration['contact_markup_string'],
    );
  }

}
