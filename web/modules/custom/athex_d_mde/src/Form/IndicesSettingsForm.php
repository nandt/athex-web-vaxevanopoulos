<?php

namespace Drupal\athex_d_mde\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class IndicesSettingsForm extends ConfigFormBase
{

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return [
            'athex_d_mde.indicessettings',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'athex_d_mde_indices_settings_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('athex_d_mde.indicessettings');

        $form['indices'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Indices'),
            '#default_value' => $config->get('indices'),
            '#description' => $this->t('Enter the indices separated by a comma. e.g. GD,FTSE'),
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('athex_d_mde.indicessettings')
            ->set('indices', $form_state->getValue('indices'))
            ->save();

        parent::submitForm($form, $form_state);
    }
}

