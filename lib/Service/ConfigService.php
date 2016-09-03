<?php

/**
 * Nextcloud - nextant
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@pontapreta.net>
 * @copyright Maxence Lange 2016
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\Nextant\Service;

use OCP\IConfig;

class ConfigService
{

    private $defaults = [
        'solr_url' => 'http://127.0.0.1:8080/solr/'
    ];

    private $appName;

    private $config;

    public function __construct($appName, IConfig $config)
    {
        $this->appName = $appName;
        $this->config = $config;
    }

    /**
     * Get a value by key
     *
     * @param string $key            
     * @return string
     */
    public function getAppValue($key)
    {
        $defaultValue = null;
        if (array_key_exists($key, $this->defaults))
            $defaultValue = $this->defaults[$key];
        return $this->config->getAppValue($this->appName, $key, $defaultValue);
    }

    /**
     * Set a value by key
     *
     * @param string $key            
     * @param string $value            
     * @return string
     */
    public function setAppValue($key, $value)
    {
        return $this->config->setAppValue($this->appName, $key, $value);
    }

    /**
     * generate an array to pass config to Solarium
     *
     * @return array
     */
    public function toSolarium($config = null)
    {
        if ($config == null || ! key_exists('solr_url', $config))
            $config['solr_url'] = $this->getAppValue('solr_url');
        
        $t = parse_url($config['solr_url']);
        return array(
            'endpoint' => array(
                'localhost' => array(
                    'host' => $t['host'],
                    'port' => $t['port'],
                    'path' => $t['path']
                )
            )
        );
    }
}