<?php

namespace Wsh\CmsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WshCmsBundle extends Bundle
{
	public function getParent()
    {
        return 'SonataAdminBundle';
    }
}
