<?php

echo $view->panel()
    ->insert($view->textInput('CN'));

echo $view->buttonList($view::BUTTON_SUBMIT | $view::BUTTON_CANCEL);
