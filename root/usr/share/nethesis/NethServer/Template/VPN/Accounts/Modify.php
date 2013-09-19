<?php

if ($view->getModule()->getIdentifier() == 'update') {
    $headerText = 'update_header_label';
} else {
    $headerText = 'create_header_label';
}

echo $view->header()->setAttribute('template',$T($headerText));

echo $view->panel()
    ->insert($view->fieldset()->setAttribute('template',$T('AccountType_label'))
    ->insert($view->fieldsetSwitch('AccountType', 'user', $view::FIELDSETSWITCH_EXPANDABLE)
    ->insert($view->selector('User', $view::SELECTOR_DROPDOWN)))
    ->insert($view->fieldsetSwitch('AccountType', 'vpn', $view::FIELDSETSWITCH_EXPANDABLE)
    ->insert($view->textInput('name'))))
    ->insert($view->textInput('VPNRemoteNetwork'))
    ->insert($view->textInput('VPNRemoteNetmask'));

echo $view->buttonList($view::BUTTON_SUBMIT | $view::BUTTON_CANCEL | $view::BUTTON_HELP);

