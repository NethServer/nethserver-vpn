<?php
namespace NethServer\Module\VPN\Certificates;

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

/**
 * Create a new x509 certificate
 *
 * @author Giacomo Sanchietti <giacomo.sanchietti@nethesis.it>
 * @since 1.0
 */
class Create extends \Nethgui\Controller\Table\AbstractAction
{

    private $cmd = "/usr/bin/sudo /usr/libexec/nethserver/pki-vpn-gencert";

    public function initialize()
    {
        parent::initialize();
        $this->declareParameter('CN', Validate::ANYTHING, NULL);
    }



    public function process()
    {
        if ($this->getRequest()->isMutation()) {
            $CN = $this->parameters['CN'];
            $process = $this->getPlatform()->exec($this->cmd." $CN");
            if ($process->getExitCode() != 0) {
                $this->getLog()->error(sprintf("%s: ".$this->cmd." %s command failed", __CLASS__, $CN));
            }
        } else {
            parent::process();
        }
    }

    public function nextPath()
    {
        if ($this->getRequest()->isMutation()) {
            return '/VPN/Certificates/read';
        }
        return parent::nextPath();
    }

}
