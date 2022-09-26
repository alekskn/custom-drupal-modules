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
 * Class GalleryBehavior
 *
 * @ParagraphsBehavior(
 *   id="mikhalych_paragraphs_gallery",
 *   label=@Translation("Gallery Settings"),
 *   description=@Translation("Settings for gallery paragraph type."),
 *   weight=0,
 * )
 */
class GalleryBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type) {
    return $paragraphs_type->id() == 'gallery';
  }

  /**
   * {@inheritdoc}
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    $images_per_row = $paragraph->getBehaviorSetting($this->getPluginId(), 'items_per_row', 4);

    $bem_block = 'paragraph-' . $paragraph->bundle();
    $bem_block .= $view_mode != 'default' ? '-' . $view_mode : false;
    $bem_block .= '--images-per-row-' . $images_per_row;

    $build['#attributes']['class'][] = Html::getClass($bem_block);
    /**
     * Image Style.
     */
    if (isset($build['field_images']) && $build['field_images']['#formatter'] == 'photoswipe_field_formatter') {
      switch ($images_per_row) {
        case 2:
          $image_style = 'paragraph_gallery_images_6_of_12';
          break;
        case 3:
          $image_style = 'paragraph_gallery_images_4_of_12';
          break;
        case 4:
        default:
          $image_style = 'paragraph_gallery_images_3_of_12';
          break;
      }

      if (ImageStyle::load($image_style)) {
        for ($i = 0; $i < count($build['field_images']['#items']); $i++) {
          $build['field_images'][$i]['#display_settings']['photoswipe_node_style'] = $image_style;
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {
    $form['items_per_row'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of images per row'),
      '#options' => [
        '2' => $this->formatPlural(2, '1 photo per row', '@count photos per row'),
        '3' => $this->formatPlural(3, '1 photo per row', '@count photos per row'),
        '4' => $this->formatPlural(4, '1 photo per row', '@count photos per row'),
      ],
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'items_per_row', 4),
    ];
    return $form;
  }

}
