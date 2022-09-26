<?php

namespace Drupal\mikhalych_paragraphs\Plugin\paragraphs\Behavior;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;

/**
 * @ParagraphsBehavior(
 *   id = "mikhalych_paragraphs_image_and_text",
 *   label = @Translation("Image and Text settings"),
 *   description = @Translation("Settings for image and text paragraph type."),
 *   weight = 0
 * )
 */
class ImageAndTextBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type) {
    return $paragraphs_type->id() == 'image_and_text';
  }

  /**
   * {@inheritdoc}
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    $bem_block = 'paragraph-' . $paragraph->bundle();
    $bem_block .= $view_mode != 'default' ? '-' . $view_mode : false;

    // Image position
    $image_position = $paragraph->getBehaviorSetting($this->getPluginId(), 'image_position', 'left');
    $build['#attributes']['class'][] = Html::getClass($bem_block . '--image-position-' . $image_position);

    // Image size
    $image_size = $paragraph->getBehaviorSetting($this->getPluginId(), 'image_size', '4_12');
    $build['#attributes']['class'][] = Html::getClass($bem_block . '--image-size-' . $image_size);

    //dump($build);

    if (isset($build['field_image']) && $build['field_image']['#formatter'] == 'photoswipe_field_formatter') {

      switch ($image_size) {
        case '4_12':
          $image_style = 'paragraph_image_and_text_image_4_of_12';
          break;
        case '6_12':
          $image_style = 'paragraph_image_and_text_image_6_of_12';
          break;
        case '8_12':
        default:
          $image_style = 'paragraph_image_and_text_image_8_of_12';
          break;
      }

      if (ImageStyle::load($image_style)) {
        $build['field_image'][0]['#display_settings']['photoswipe_node_style'] = $image_style;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {

    $form['image_position'] = [
      '#type' => 'select',
      '#title' => $this->t('Image position'),
      '#options' => [
        'left' => $this->t('Left'),
        'right' => $this->t('Right'),
      ],
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'image_position', 'left'),
    ];

    $form['image_size'] = [
      '#type' => 'select',
      '#title' => $this->t('Image size'),
      '#description' => $this->t('Size of the image in grid.'),
      '#options' => [
        '4_12' => $this->formatPlural(4, '1 column of 12', '@count columns of 12'),
        '6_12' => $this->formatPlural(6, '1 column of 12', '@count columns of 12'),
        '8_12' => $this->formatPlural(8, '1 column of 12', '@count columns of 12'),
      ],
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'image_size', '4_12'),
    ];

    return $form;
  }

}
