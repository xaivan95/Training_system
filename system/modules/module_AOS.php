<?php

class module_AOS extends module_base
{
    /**
     * Implementation of module_base::view().
     */
    function view()
    {
       include( 'index.html');
      return true;   
    }
}

