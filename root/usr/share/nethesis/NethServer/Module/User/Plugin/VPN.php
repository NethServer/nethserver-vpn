<?php
namespace NethServer\Module\User\Plugin;

/*
 * Copyright (C) 2011 Nethesis S.r.l.
 * 
 * This script is part of NethServer.
 * 
 * NethServer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * NethServer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with NethServer.  If not, see <http://www.gnu.org/licenses/>.
 */

use Nethgui\System\PlatformInterface as Validate;
use Nethgui\Controller\Table\Modify as Table;

/**
 * Enable or disable VPN access
 */
class VPN extends \Nethgui\Controller\Table\RowPluginAction
{
    protected function initializeAttributes(\Nethgui\Module\ModuleAttributesInterface $base)
    {
        return \Nethgui\Module\SimpleModuleAttributesProvider::extendModuleAttributes($base, 'Service', 40);
    }
    
    public function initialize()
    
    {
        $this->setSchemaAddition(array(
            array('vpn', Validate::BOOLEAN, Table::FIELD, 'VPNClientAccess'),
        ));
        
        parent::initialize();
    }

    public function readVpn($dbValue)
    {
        if ($dbValue == 'yes') {
            return '1';
        }

        return '';
    }

    public function writeVpn($formInput)
    {
        if ($formInput == '1') {
            return array('yes');
        }

        return array('no');
    }

}
