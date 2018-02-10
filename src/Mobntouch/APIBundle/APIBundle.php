<?php

namespace Mobntouch\APIBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class APIBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
