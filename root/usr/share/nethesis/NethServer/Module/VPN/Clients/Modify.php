<?php
namespace NethServer\Module\VPN\Clients;

/*
 * Copyright (C) 2012 Nethesis S.r.l.
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
 * Modify VPN clients (tunnels)
 *
 * @author Giacomo Sanchietti <giacomo.sanchietti@nethesis.it>
 */
class Modify extends \Nethgui\Controller\Table\Modify
{

    public function initialize()
    {
        $portRangeValidator = $this->createValidator()
            ->orValidator(
                $this->createValidator()->integer()->greatThan(0)->lessThan(65535),
                $this->createValidator()->regexp('/^[0-9]+\:[0-9]+$/') #port range, no check on maximum value
            );


        $parameterSchema = array(
            array('name', Validate::USERNAME, \Nethgui\Controller\Table\Modify::KEY),
            array('Mode', Validate::ANYTHING, \Nethgui\Controller\Table\Modify::FIELD),
            array('Password', Validate::ANYTHING, \Nethgui\Controller\Table\Modify::FIELD),
            array('Psk', Validate::ANYTHING, \Nethgui\Controller\Table\Modify::FIELD),
            array('Crt', Validate::ANYTHING, \Nethgui\Controller\Table\Modify::FIELD),
            array('RemoteHost', Validate::ANYTHING, \Nethgui\Controller\Table\Modify::FIELD), //TODO
            array('RemotePort', $portRangeValidator, \Nethgui\Controller\Table\Modify::FIELD),
            array('User', Validate::ANYTHING, \Nethgui\Controller\Table\Modify::FIELD),
            array('VPNType', Validate::ANYTHING, \Nethgui\Controller\Table\Modify::FIELD), //TODO
            array('AuthMode', Validate::ANYTHING, \Nethgui\Controller\Table\Modify::FIELD), //TODO
        );

        $this->setSchema($parameterSchema);
        $this->setDefaultValue('Mode', 'routed');
        $this->setDefaultValue('VPNType', 'openvpn');
        $this->setDefaultValue('AuthMode', 'certificate');

        parent::initialize();
    }

    public function prepareView(\Nethgui\View\ViewInterface $view)
    {
        parent::prepareView($view);
        $templates = array(
            'create' => 'NethServer\Template\VPN\Clients\Modify',
            'update' => 'NethServer\Template\VPN\Clients\Modify',
            'delete' => 'Nethgui\Template\Table\Delete',
        );
        $view->setTemplate($templates[$this->getIdentifier()]);

        $view['ModeDatasource'] =  array_map(function($fmt) use ($view) {
            return array($fmt, $view->translate($fmt . '_label'));
        }, array('routed', 'bridged'));


    }


    public function process()
    {
        parent::process();
    }


    protected function onParametersSaved($changedParameters)
    {
        #$this->exitCode = $this->getPlatform()->signalEvent('firewall-adjust')->getExitCode();
    }

}
