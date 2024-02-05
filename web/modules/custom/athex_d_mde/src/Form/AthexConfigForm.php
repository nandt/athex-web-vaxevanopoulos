<?php

namespace Drupal\athex_d_mde\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class AthexConfigForm extends ConfigFormBase
{

	/**
	 * {@inheritdoc}
	 */
	protected function getEditableConfigNames()
	{
		return ['athex_d_mde.settings'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormId()
	{
		return 'athex_d_mde_admin_settings';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state)
	{
		$config = $this->config('athex_d_mde.settings');

		$form['gd_values'] = [
			'#type' => 'textfield',
			'#title' => $this->t('GD Values'),
			'#default_value' => $config->get('gd_values'),
			'#description' => $this->t('Enter the GD values separated by commas, e.g., "GD,FTSE,ETE,ALPHA,TPEIR,EXAE".'),
		];

		return parent::buildForm($form, $form_state);
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state)
	{
		parent::submitForm($form, $form_state);

		$this->config('athex_d_mde.settings')
			->set('gd_values', $form_state->getValue('gd_values'))
			->save();
	}
}
