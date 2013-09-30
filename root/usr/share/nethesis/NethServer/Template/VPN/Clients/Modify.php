<?php

if ($view->getModule()->getIdentifier() == 'update') {
    $headerText = 'update_header_label';
} else {
    $headerText = 'create_header_label';
}

echo $view->header()->setAttribute('template',$T($headerText));

echo $view->panel()
    ->insert($view->textInput('name'))
    ->insert($view->fieldset()->setAttribute('template', $T('VPNType_label'))
    ->insert($view->fieldsetSwitch('VPNType', 'openvpn', $view::FIELDSETSWITCH_EXPANDABLE)
        ->insert($view->textInput('RemoteHost'))
        ->insert($view->textInput('RemotePort'))
        ->insert($view->selector('Mode')))
    ->insert($view->fieldsetSwitch('VPNType', 'ipsec', $view::FIELDSETSWITCH_EXPANDABLE)
        ->insert($view->textInput('RemoteHost'))));

echo $view->fieldset()->setAttribute('template', $T('AuthMode_label'))
    ->insert($view->fieldsetSwitch('AuthMode', 'certificate',$view::FIELDSETSWITCH_EXPANDABLE)
        ->insert($view->textArea('Crt'))
    )
    ->insert($view->fieldsetSwitch('AuthMode', 'password',$view::FIELDSETSWITCH_EXPANDABLE)
        ->insert($view->textInput('User'))
        ->insert($view->textInput('Password'))
    )
    ->insert($view->fieldsetSwitch('AuthMode', 'password-certificate',$view::FIELDSETSWITCH_EXPANDABLE)
        ->insert($view->textInput('User'))
        ->insert($view->textInput('Password'))
        ->insert($view->textArea('Crt'))
    )
    ->insert($view->fieldsetSwitch('AuthMode', 'psk',$view::FIELDSETSWITCH_EXPANDABLE)
        ->insert($view->textInput('Psk'))
    );

echo $view->buttonList($view::BUTTON_SUBMIT | $view::BUTTON_CANCEL | $view::BUTTON_HELP);
