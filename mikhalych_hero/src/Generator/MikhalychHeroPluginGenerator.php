<?php

namespace Drupal\mikhalych_hero\Generator;


use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\Utils;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Mikhalych hero plugin generator for drush.
 */
class MikhalychHeroPluginGenerator extends BaseGenerator {

  /**
   * {@inheritdoc}
   */
  protected $name = 'plugin-mikhalych-hero';

  /**
   * {@inheritdoc}
   */
  protected $alias = 'mikhalych-hero';

  /**
   * {@inheritdoc}
   */
  protected $description = 'Generates MikhalychHero plugin.';

  /**
   * {@inheritdoc}
   */
  protected $templatePath = __DIR__ . '/templates';

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * MikhalychHeroPluginGenerator constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity manager.
   * @param null $name
   *   The command name.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, $name = NULL) {
    parent::__construct($name);

    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function interact(InputInterface $input, OutputInterface $output) {
    $questions = Utils::pluginQuestions();
    unset($questions['class']);
    $questions = Utils::moduleQuestions() + $questions;
    $this->askForPluginType($questions);

    $questions['is_title'] = new ConfirmationQuestion(t('Do you want to customize title?'), FALSE);
    $questions['is_subtitle'] = new ConfirmationQuestion(t('Do you want to add subtitle?'), FALSE);
    $questions['is_image'] = new ConfirmationQuestion(t('Do you want to specify image?'), FALSE);
    $questions['is_video'] = new ConfirmationQuestion(t('Do you want to specify video?'), FALSE);

    $vars = &$this->collectVars($input, $output, $questions);
    $vars['name'] = Utils::camelize($vars['plugin_id']);
    $vars['type'] = Utils::camelize($vars['mikhalych_hero_plugin_type']);
    $vars['twig_template'] = 'mikhalych-hero-' . $vars['mikhalych_hero_plugin_type'] . '-plugin.html.twig';

    // Additional questions.
    $questions = [];

    if ($vars['mikhalych_hero_plugin_type'] == 'path') {
      $questions['match_type'] = new ChoiceQuestion(t('Match type for path'), [
        'listed' => t('Only on listed page'),
        'unlisted' => t('All except listed'),
      ]);
    }

    if ($vars['mikhalych_hero_plugin_type'] == 'entity') {
      $entity_types = [];
      foreach ($this->entityTypeManager->getDefinitions() as $entity_type_id => $entity_type) {
        if ($entity_type instanceof ContentEntityType) {
          $entity_types[$entity_type_id] = $entity_type->getLabel();
        }
      }
      $questions['entity_type'] = new ChoiceQuestion(t('Entity type'), $entity_types);
    }

    $vars = &$this->collectVars($input, $output, $questions, $vars);
    //dump($vars);

    $this->addFile()
      ->path('src/Plugin/MikhalychHero/{type}/{name}.php')
      ->template($vars['twig_template']);
  }

  /**
   * Asks for preferred plugin type.
   */
  public function askForPluginType(&$question) {
    $mikhalych_hero_plugin_types = [
      'path' => 'MikhalychHero Path plugin',
      'entity' => 'MikhalychHero Entity plugin',
    ];

    $question['mikhalych_hero_plugin_type'] = new ChoiceQuestion(
      t('What plugin type do you want to create?'),
      $mikhalych_hero_plugin_types
    );
  }

}
