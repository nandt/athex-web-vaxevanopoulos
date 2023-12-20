<?php

namespace Drupal\athex_sis_integration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase {

	public function getFormId() {
        return 'athex_sis_settings_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = parent::buildForm($form, $form_state);

        $config = $this->config('athex_sis.settings');

		$form['api_settings'] = [
			'username' => [
				'#type' => 'textfield',
				'#title' => $this->t('Username'),
				'#default_value' => $config->get('username'),
				'#required' => TRUE
			],
			'password' => [
				'#type' => 'textfield',
				'#title' => $this->t('Password'),
				'#default_value' => $config->get('password'),
				'#required' => TRUE
			],
			'connection_string' => [
				'#type' => 'textfield',
				'#title' => $this->t('Connection String'),
				'#default_value' => $config->get('connection_string'),
				'#required' => TRUE
			]
		];

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        return parent::validateForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $config = $this->config('athex_sis.settings');

        foreach ($form_state->getValues() as $key => $value)
			$config->set($key, $value);

        $config->save();

        return parent::submitForm($form, $form_state);
    }

    protected function getEditableConfigNames() {
        return [
            'athex_sis.settings',
        ];
    }

}
