<?php

namespace Drupal\mikhalych_hero\Plugin\MikhalychHero\Path;

use Drupal\mikhalych_hero\Annotation\MikhalychHeroPath;

/**
 * Default plugin which will be used if non of others met their requirements.
 *
 * @MikhalychHeroPath(
 *   id="mikhalych_hero_path_default",
 *   match_path={"*"},
 *   weight=-100
 * )
 */
class MikhalychHeroPathDefaultPlugin extends MikhalychHeroPathPluginBase {

}
