<?php

namespace Drupal\rowan_forms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a simple example form.
 */
class SimpleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_example_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['introduction'] = [
      '#type' => 'HTML',
      '#markup' => '<h1>Simple Form</h1>
<p>This is an example of a simple form you can create in Drupal, This includes a submission message and validation on the phone number element.</p>',
    ];

    $form['feedback']['feedback_description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Feedback'),
      '#placeholder' => $this->t('Please enter your feedback here...'),
    ];

    $form['feedback']['feedback_rating'] = [
      '#type' => 'radios',
      '#title' => $this->t('How did you find our services today?'),
      '#required' => TRUE,
      '#default_value' => 4,
      '#options' => [
        0 => $this->t('1 - Poor'),
        1 => $this->t('2'),
        2 => $this->t('3'),
        3 => $this->t('4'),
        4 => $this->t('5 - Great'),
      ],
    ];

    $form['personal_details']['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#required' => TRUE,
    ];

    $form['personal_details']['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#required' => TRUE,
    ];

    $form['personal_details']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];

    $form['personal_details']['phone_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone number'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('phone_number')) < 10) {
      $form_state->setErrorByName('phone_number', $this->t('The phone number is too short. Please enter a full phone number.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('feedback_rating') !== (3 || 4)) {
      $this->messenger()->addStatus($this->t("We're sorry you had a bad experience! Please get in contact at test@test.com"));
    }
  }

}
