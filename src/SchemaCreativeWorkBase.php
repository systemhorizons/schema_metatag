<?php

/**
 * Schema.org CreativeWork items should extend this class.
 */
class SchemaCreativeWorkBase extends SchemaNameBase {

  use SchemaCreativeWorkTrait;

  /**
   * {@inheritdoc}
   */
  public function getForm(array $options = []) {

    $value = SchemaMetatagManager::unserialize($this->value());

    $input_values = [
      'title' => $this->label(),
      'description' => $this->description(),
      'value' => $value,
      '#required' => isset($options['#required']) ? $options['#required'] : FALSE,
      'visibility_selector' => $this->visibilitySelector(),
    ];

    $form['value'] = $this->creativeWorkForm($input_values);

    if (empty($this->multiple())) {
      unset($form['value']['pivot']);
    }

    // Validation from parent::getForm() got wiped out, so add callback.
    $form['value']['#element_validate'][] = 'schema_metatag_element_validate';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public static function testValue() {
    $items = [];
    $keys = self::creativeWorkFormKeys('CreativeWork');
    foreach ($keys as $key) {
      switch ($key) {

        case '@type':
          $items[$key] = 'CreativeWork';
          break;

        case 'author':
          $items[$key] = SchemaPersonOrgBase::testValue();
          break;

        case 'potentialAction':
          $items[$key] = SchemaActionBase::testValue();
          break;

        default:
          $items[$key] = parent::testDefaultValue(1, '');
          break;

      }
    }
    return $items;
  }

}
