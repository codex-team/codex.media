<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 * @author CodeX Team
 * @copyright (c) 2018 CodeX Team
 *
 * Config overrides for the CodeX Media
 *
 * Inherit base config with Project Contfig
 * from /project/<name>/config/
 */
class Config extends Kohana_Config
{
    /**
     * Configs folder name
     * @var string
     */
    protected $config_path = 'config';

    /**
     * Load a configuration group. Searches all the config sources, merging all the
     * directives found into a single config group.  Any changes made to the config
     * in this group will be mirrored across all writable sources.
     *
     *     $array = $config->load($name);
     *
     * See [Kohana_Config_Group] for more info
     *
     * @param   string  $group  configuration group name
     * @return  Kohana_Config_Group
     * @throws  Kohana_Exception
     */
    public function load($group)
    {
        $projectConfigDir = '../projects' . DIRECTORY_SEPARATOR . Arr::get($_SERVER, 'PROJECT', '') . DIRECTORY_SEPARATOR . $this->config_path;
        $projectConfig = Kohana::find_file($projectConfigDir, $group);

        /**
         * If Project's Config found, use this Config File instead of base
         */
        if ($projectConfig !== false){
            $configFile = new Kohana_Config_File($projectConfigDir);
            $config = $configFile->load($group);

            $this->_groups[$group] = new Config_Group($this, $group, $config);

            return $this->_groups[$group];
        }

        return parent::load($group);
    }
}
