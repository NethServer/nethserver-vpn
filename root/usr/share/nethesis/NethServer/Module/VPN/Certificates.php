<?php
namespace NethServer\Module\VPN;

/*
 * Copyright (C) 2013 Nethesis S.r.l.
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
 * Create and revoke VPN x509 certificates using a OpenSSL CA database.
 *
 * CA database file consists of zero or more lines, each containing the following fields separated by tab characters:
 * Certificate status flag (V=valid, R=revoked, E=expired).
 * Certificate expiration date in YYMMDDHHMMSSZ format.
 * Certificate revocation date in YYMMDDHHMMSSZ[,reason] format. Empty if not revoked.
 * Certificate serial number in hex.
 * Certificate filename or literal string ‘unknown’.
 * Certificate distinguished name.
 *
 * @author Giacomo Sanchietti <giacomo.sanchietti@nethesis.it>
 * @since 1.0
 */
class Certificates extends \Nethgui\Controller\TableController
{

    private $adapter;
    private $certindex = "/var/lib/nethserver/certs/certindex";

    private function formatDate($date)
    {
        if (!trim($date)) {
            return "-";
        }
        return "20{$date[0]}{$date[1]}-{$date[2]}{$date[3]}-{$date[4]}{$date[5]}";
    }

    private function parseCN($str) 
    {
        if (!trim($str)) {
            return "-";
        }
        $tmp = explode("/",$str);
        $tmp = explode("=",$tmp[6]);
        return $tmp[1];
    }

    public function readCertIndex() 
    {
        $loader = new \ArrayObject();

        $lines = file($this->certindex);
        if ($lines !== FALSE) {
            foreach ($lines as $line) {
                list($status, $exp_date, $rev_date, $index, $name, $cn) = explode("\t", trim($line, "\n"));

                $cn = $this->parseCN($cn);
                $loader[$cn] = array(
                    'CN' => $cn,        
                    'Status' => $status,
                    'Expiration' => $this->formatDate($exp_date),
                    'Revocation' => $this->formatDate($rev_date),        
                );
            }
        } else {
            $this->getLog()->error("Can't access certificate index file: ".$this->certindex);
        }
        return $loader;
    }

    public function initialize()
    {
        $columns = array(
            'CN',
            'Status',
            'Expiration',
            'Revocation',
            'Actions'
        );

        $this
            ->setTableAdapter(new \Nethgui\Adapter\LazyLoaderAdapter(array($this, 'readCertIndex')))
            ->setColumns($columns)
            ->addRowAction(new Certificates\Revoke())
            ->addTableAction(new Certificates\Create())
            ->addTableAction(new \Nethgui\Controller\Table\Help('Help'))
        ;

        parent::initialize();
    }

    public function prepareViewForColumnStatus(\Nethgui\Controller\Table\Read $action, \Nethgui\View\ViewInterface $view, $key, $values, &$rowMetadata)
    {
        return $view->translate($values['Status']."_label");
    }
    
    public function prepareViewForColumnActions(\Nethgui\Controller\Table\Read $action, \Nethgui\View\ViewInterface $view, $key, $values, &$rowMetadata)
    {
        $cellView = $action->prepareViewForColumnActions($view, $key, $values, $rowMetadata);
        if ($values['Status'] === 'R') {
            unset($cellView['Revoke']);
        }
        return $cellView;
    }

}
