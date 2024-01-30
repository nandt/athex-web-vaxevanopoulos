<?php
namespace Drupal\athex_d_mde\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class TickerTapeSettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['athex_d_mde.tickertape'];
  }

  public function getFormId() {
    return 'athex_d_mde_ticker_tape_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('athex_d_mde.tickertape');

    $form['codes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Ticker Tape Codes'),
      '#default_value' => $config->get('codes'),
      '#description' => $this->t('Enter the codes separated by a comma. e.g., GD.ATH,TPEIR.ATH,EXAE.ATH'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('athex_d_mde.tickertape')
      ->set('codes', $form_state->getValue('codes'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
