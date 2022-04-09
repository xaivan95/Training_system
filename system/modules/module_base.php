<?php

abstract class module_base
{
    abstract function view();

    function delete()
    {
        $this->view();
    }
}

