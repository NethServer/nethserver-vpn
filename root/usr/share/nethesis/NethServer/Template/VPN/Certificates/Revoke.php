<?php

/* @var $view Nethgui\Renderer\Xhtml */
$view->requireFlag($view::INSET_DIALOG | $view::INSET_FORM);

echo $view->header('CN')->setAttribute('template', $T('Revoke_Header'));

echo $view->textLabel('CN')->setAttribute('template', $T('Revoke_Message'))->setAttribute('tag', 'div');

echo $view->buttonList()
    ->insert($view->button('Revoke', $view::BUTTON_SUBMIT))
    ->insert($view->button('Cancel', $view::BUTTON_CANCEL))
;
