<?php

namespace Ism\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class IsmUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}
