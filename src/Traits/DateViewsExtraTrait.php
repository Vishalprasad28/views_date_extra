<?php

namespace Drupal\views_date_extra\Traits;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Shared code between the YearFilterDate and YearFilterDatetime plugins.
 */
trait DateViewsExtraTrait {

  use StringTranslationTrait;

  protected $months = [
    NULL => '-Any-',
    'JANUARY' => 'January',
    'FEBRUARY' => 'February',
    'MARCH' => 'March',
    'APRIL' => 'April',
    'MAY' => 'May',
    'JUNE' => 'June',
    'JULY' => 'July',
    'AUGUST' => 'August',
    'SEPTEMBER' => 'September',
    'OCTOBER' => 'October',
    'NOVEMBER' => 'November',
    'DECEMBER' => 'December',
];


  protected $quarters = [
    NULL => '-Any-',
    'January 1' => '1st Quarter',
    'April 1' => '2nd Quarter',
    'July 1' => '3rd Quarter',
    'October 1' => '4th Quarter',
  ];

  protected $filterMappedOperator = [
    'date_year' => 'YEAR',
    'date_month' => 'MONTH',
    'date_quarter' => 'QUARTER',
  ];
  
  /**
   * Apply the HTML5 date popup to the views filter form.
   *
   * @param array $form
   *   The form to apply it to.
   */
  protected function applyDatePopupToForm(array &$form) {
    $module_handler = \Drupal::service('module_handler');
    $identifier = $this->options['expose']['identifier'];

    // Identify wrapper.
    $wrapper_key = $identifier . '_wrapper';
    if (isset($form[$wrapper_key])) {
      $element = &$form[$wrapper_key][$identifier];
    }
    else {
      $element = &$form[$identifier];
    }

    // If the date pop module is enabled change the element type to date
    // instead of textfield.
    if (
      $module_handler->moduleExists('date_popup') &&
      isset($this->options['value']['type']) &&
      $this->options['value']['type'] !== 'date_year'
    ) {
      // Detect filters that are using min/max.
      if (isset($element['min'])) {
        $element['min']['#type'] = 'date';
        $element['max']['#type'] = 'date';
        if (isset($element['value'])) {
          $element['value']['#type'] = 'date';
        }
      }
      else {
        $element['#type'] = 'date';
      }
    }

    // Add Bootstrap Datepicker attributes.
    if (isset($this->options['value']['type']) && $this->options['value']['type'] === 'date_year') {
        // Disable autocomplete widget.
        $element['#attributes']['autocomplete'] = 'off';
        // Add class to initiate datepicker.
        $element['#attributes']['class'][] = 'js-datepicker-years-filter';
        $element['#attached']['library'][] = 'views_date_extra/datepicker';
    }
  }

}
