<?php

/**
 * @file
 * Theme settings form.
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function seventeen_form_system_theme_settings_alter(array &$form, FormStateInterface &$form_state): void {
  $form['seventeen'] = [
    '#type' => 'details',
    '#title' => t('Extra'),
    '#open' => TRUE,
  ];

  $form['seventeen']['sticky_form_actions'] = [
    '#type' => 'radios',
    '#title' => t('Sticky form actions'),
    '#options' => [
      'always' => t('Always'),
      'auto' => t('Auto'),
      'never' => t('Never'),
    ],
    '#default_value' => theme_get_setting('sticky_form_actions'),
  ];

  $form['seventeen']['container_width'] = [
    '#type' => 'number',
    '#title' => t('Container width'),
    '#min' => 680,
    '#max' => 2360,
    '#step' => 120,
    '#size' => 30,
    '#default_value' => theme_get_setting('container_width'),
    '#description' => t('The maximum width of the content area in pixels.'),
  ];

  $form['seventeen']['font_size'] = [
    '#type' => 'number',
    '#title' => t('Font size'),
    '#min' => 8,
    '#max' => 8000,
    '#default_value' => theme_get_setting('font_size'),
  ];

  $fonts[''] = t('- Default -');

  $fonts['Standard'] = [
    'impact' => 'Impact',
    'palatino_linotype' => 'Palatino Linotype',
    'tahoma' => 'Tahoma',
    'century-gothic' => 'Century Gothic',
    'lucida-sans-unicode' => 'lucida Sans Unicode',
    'arial-black' => 'Arial Black',
    'times-new-roman' => 'Times New Roman',
    'arial-warrow' => 'Arial Narrow',
    'verdana' => 'Verdana',
    'copperplate-gothic_light' => 'Copperplate Gothic Light',
    'lucida-console' => 'Lucida Console',
    'gill-sans' => 'Gill Sans',
    'trebuchet-ms' => 'Trebuchet MS',
    'courier-new' => 'Courier New',
    'arial' => 'Arial',
    'georgia' => 'Georgia',
  ];

  $fonts['Google fonts'] = [
    'ubuntu' => 'Ubuntu ',
    'open-sans' => 'Open Sans',
    'josefin-slab' => 'Josefin Slab',
    'pt-sans' => 'PT Sans',
    'roboto' => 'Roboto',
    'source-sans-pro' => 'Sanc Pro',
    'exo' => 'Exo',
  ];

  $form['seventeen']['font_family'] = [
    '#type' => 'select',
    '#title' => t('Font family'),
    '#options' => $fonts,
    '#default_value' => theme_get_setting('font_family'),
  ];

}
