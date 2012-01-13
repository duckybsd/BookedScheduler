<?php
/**
Copyright 2011-2012 Nick Korbel

This file is part of phpScheduleIt.

phpScheduleIt is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

phpScheduleIt is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with phpScheduleIt.  If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * Include plugins
 */
require_once(ROOT_DIR . 'lib/Config/namespace.php'); // namespace.php is an include files of classes

class PluginManager
{

    /**
     * @var PluginManager
     */
    private static $_instance = null;

    /**
     * Empty constructor is legal
     */
    private function __construct()
    {

    }

    /**
     * @static
     * @return PluginManager
     */
    public static function Instance()
    {
        /**
         * $_instance variable seems to be always Null.
         */
        if (is_null(self::$_instance))
        {
            self::$_instance = new PluginManager();
        }
        return self::$_instance;
    }

    /**
     * @static
     * @param $pluginManager PluginManager
     * @return void
     */
    public static function SetInstance($pluginManager)
    {
        self::$_instance = $pluginManager;
    }

    /**
     * Loads the configured Authentication plugin, if one exists
     * If no plugin exists, the default Authentication class is returned
     *
     * @return IAuthentication the authorization class to use
     */
    public function LoadAuthentication()
    {
        require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
        /**
         * Instantiate Authentication class object with
         */
        $authentication = new Authentication($this->LoadAuthorization());
        /**
         * param#1: ConfigKeys::PLUGIN_AUTHENTICATION is a constance, which provides type of authentication.
         * param#2: 'Authentication' is a constant for plugin sub-directory
         * param#3: $authentication is actually the authoriaztion of this authentication.
         */
        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_AUTHENTICATION, 'Authentication', $authentication);

        if (!is_null($plugin))
        {
            /**
             * Returning authentication plugin
             */
            return $plugin;
        }

        return $authentication;
    }

    /**
     * Loads the configured Permission plugin, if one exists
     * If no plugin exists, the default PermissionService class is returned
     *
     * @return IPermissionService
     */
    public function LoadPermission()
    {
        require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');

        $resourcePermissionStore = new ResourcePermissionStore(new ScheduleUserRepository());
        $permissionService = new PermissionService($resourcePermissionStore);

        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_PERMISSION, 'Permission', $permissionService);

        if (!is_null($plugin))
        {
            return $plugin;
        }

        return $permissionService;
    }

    /**
     * Loads the configured Authorization plugin, if one exists
     * If no plugin exists, the default PermissionService class is returned
     *
     * @return IAuthorizationService
     */
    public function LoadAuthorization()
    {
        require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');

        $authorizationService = new AuthorizationService(new UserRepository());

        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_AUTHORIZATION, 'Authorization', $authorizationService);

        if (!is_null($plugin))
        {
            return $plugin;
        }

        return $authorizationService;
    }

    /**
     * Loads the configured PreReservation plugin, if one exists
     * If no plugin exists, the default PreReservationFactory class is returned
     *
     * @return IPreReservationFactory
     */
    public function LoadPreReservation()
    {
        require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

        $factory = new PreReservationFactory();

        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_PRERESERVATION, 'PreReservation', $factory);

        if (!is_null($plugin))
        {
            return $plugin;
        }

        return $factory;
    }

    /**
     * @param string $configKey key to use
     * @param string $pluginSubDirectory subdirectory name under 'plugins'
     * @param mixed $baseImplementation the base implementation of the plugin.  allows decorating
     * @return mixed|null plugin implementation
     */
    private function LoadPlugin($configKey, $pluginSubDirectory, $baseImplementation)
    {
        $plugin = Configuration::Instance()->GetSectionKey(ConfigSection::PLUGINS, $configKey);
        $pluginFile = ROOT_DIR . "plugins/$pluginSubDirectory/$plugin/$plugin.php";

        if (!empty($plugin) && file_exists($pluginFile))
        {
            require_once($pluginFile);
            return new $plugin($baseImplementation);
        }

        return null;
    }

}

?>