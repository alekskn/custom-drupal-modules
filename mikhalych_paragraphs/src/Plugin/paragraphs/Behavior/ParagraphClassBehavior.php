<?php

namespace Drupal\mikhalych_paragraphs\Plugin\paragraphs\Behavior;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;

/**
 * @ParagraphsBehavior(
 *   id = "mikhalych_paragraphs_paragraph_class",
 *   label = @Translation("Custom classes for paragraphs"),
 *   description = @Translation("Allows to add custom classes to paragraph."),
 *   weight = 0
 * )
 */
class ParagraphClassBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    $classes_value = $paragraph->getBehaviorSetting($this->getPluginId(), 'classes', '');
    $classes = explode(' ', $classes_value);

    foreach ($classes as $class) {
      $build['#attributes']['class'][] = Html::getClass($class);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {

    $form['classes'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Classes'),
      '#description' => $this->t('Multiple classes separated by space. They will be processed via Html::getClass().'),
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'classes', ''),
    ];

    return $form;
  }

}
