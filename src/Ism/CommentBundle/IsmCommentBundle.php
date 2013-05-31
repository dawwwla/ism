<?php

namespace Ism\CommentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class IsmCommentBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSCommentBundle';
  }
}
