<?php

namespace Drupal\athex_inbroker_integration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase {

	public function getFormId() {
        return 'athex_inbroker_settings_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = parent::buildForm($form, $form_state);

        $config = $this->config('athex_inbroker.settings');

		$form['api_settings'] = [
			// 'realtime_host' => [
			// 	'#type' => 'textfield',
			// 	'#title' => $this->t('Realtime Data Host'),
			// 	'#default_value' => $config->get('realtime_host'),
			// 	'#required' => TRUE,
			// ],
			'delayed_host' => [
				'#type' => 'textfield',
				'#title' => $this->t('Delayed Data Host'),
				'#default_value' => $config->get('delayed_host'),
				'#required' => TRUE,
			],
			'args' => [
				'userName' => [
					'#type' => 'textfield',
					'#title' => $this->t('Username'),
					'#default_value' => $config->get('userName'),
					'#required' => TRUE,
				],
				'company' => [
					'#type' => 'textfield',
					'#title' => $this->t('Company ID'),
					'#default_value' => $config->get('company'),
					'#required' => TRUE,
				],
				'IBSessionId' => [
					'#type' => 'textfield',
					'#title' => $this->t('IBSessionId'),
					'#default_value' => $config->get('IBSessionId'),
					'#required' => TRUE,
				]
			]
		];

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        return parent::validateForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $config = $this->config('athex_inbroker.settings');

        foreach ($form_state->getValues() as $key => $value)
			$config->set($key, $value);

        $config->save();

        return parent::submitForm($form, $form_state);
    }

    protected function getEditableConfigNames() {
        return [
            'athex_inbroker.settings',
        ];
    }

}
