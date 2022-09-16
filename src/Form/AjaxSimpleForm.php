<?php

namespace Drupal\rowan_forms\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a simple example form with Ajax integration.
 */
class AjaxSimpleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_example_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['introduction'] = [
      '#type' => 'HTML',
      '#markup' => '<h1>Simple Ajax Form</h1>
<p>This is an example of a simple form with Ajax integration, .</p>',
    ];

    $form['ajax_select'] = [
      '#type' => 'select',
      '#title' => $this->t('Select element'),
      '#options' => [
        '1' => $this->t('One'),
        '2' => $this->t('Two'),
        '3' => $this->t('Three'),
      ],
      '#ajax' => [
        'callback' => '::ajaxEventCallback',
        'disable-refocus' => TRUE,
        'event' => 'change',
        'wrapper' => 'response-element',
      ],
    ];

    $form['ajax_output'] = [
      '#type' => 'textfield',
      '#description' => 'This field will change when the Ajax event has triggered.',
      '#disabled' => TRUE,
      '#value' => 'Waiting for event',
      '#prefix' => '<div id="response-element">',
      '#suffix' => '</div>',
    ];

    $form['basic_page'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Basic page Selector'),
      '#description' => $this->t('In this example we are using an Ajax Response to search for basic pages and create a working autocomplete'),
      '#target_type' => 'node',
      '#selection_settings' => [
        'target_bundles' => ['page'],
      ],
      '#ajax' => [
        'callback' => '::populateFields',
        'event' => 'autocompleteclose',
      ],
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
   * The Ajax event callback method triggered by changing the select element.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return mixed
   *   Returns the string set to be used in our form item.
   */
  public function ajaxEventCallback(array &$form, FormStateInterface $form_state) {

    if ($form_state->getValue('ajax_select')) {
      $form['ajax_output']['#value'] = "the event has triggered";
    }

    return $form['ajax_output'];
  }

  /**
   * Ajax response method which will return the basic pages author date value.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Returns the Ajax response containing the node author date value.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function populateFields(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $nid = $form_state->getValue('page');
    if (!empty($nid)) {
      $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
      $response->addCommand(new InvokeCommand('#edit-authored-on', 'val', [$node->getCreatedTime()]));
    }

    return $response;
  }

  /**
   * The submit form function.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
