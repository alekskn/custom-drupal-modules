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
 *   id = "mikhalych_paragraphs_code_line_highlight",
 *   label = @Translation("Code line highlight"),
 *   description = @Translation("Highlight code line for focusing."),
 *   weight = 0
 * )
 */
class CodeLineHighlight extends ParagraphsBehaviorBase {

  /**
   * {@inheritDoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type) {
    return $paragraphs_type->id() == 'code';
  }

  /**
   * @inheritDoc
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    $highlighted_lines = $paragraph->getBehaviorSetting($this->getPluginId(), 'highlighted_lines', false);
    if ($highlighted_lines) {
      $build['#attached']['library'][] = 'mikhalych_paragraphs/highlighted_lines';
      $build['#attributes']['data-highlighted-lines'] = $highlighted_lines;
    }
  }

  /**
   * {@inheritDoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {

    $form['highlighted_lines'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Highlighted lines'),
      '#description' => $this->t('Separate line numbers with commas and range with :.'),
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'highlighted_lines', false)
    ];

    return $form;
  }

}
