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
 *   id = "mikhalych_paragraphs_paragraph_title",
 *   label = @Translation("Paragraph title settings"),
 *   description = @Translation("Allows to configure paragraph title behavior."),
 *   weight = 0
 * )
 */
class ParagraphTitleBehavior extends ParagraphsBehaviorBase {

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

  }

  public function preprocess(&$variables) {
    /** @var \Drupal\paragraphs\ParagraphInterface $paragraph */
    $paragraph = $variables['paragraph'];
    $variables['title_wrapper'] = $paragraph->getBehaviorSetting($this->getPluginId(), 'title_wrapper', 'h2');
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {

    if ($paragraph->hasField('field_title')) {
      $form['title_wrapper'] = [
        '#type' => 'select',
        '#title' => $this->t('Title wrapper element'),
        '#options' => $this->getHeadingOptions(),
        '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'title_wrapper', 'h2'),
      ];
    }

    return $form;
  }

  /**
   * Defines list of available options for title element.
   */
  public function getHeadingOptions() {
    return [
      'h2' => 'h2',
      'h3' => 'h3',
      'h4' => 'h4',
      'div' => 'div',
    ];
  }

}
