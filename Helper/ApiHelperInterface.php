<?php

namespace KRG\AddressBundle\Helper;

interface ApiHelperInterface
{
    public function render($callback = null, $async = true, $defer = true);
}
