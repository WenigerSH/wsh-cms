<?php

namespace spec\Wsh\CmsBundle\Entity;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Wsh\CmsBundle\Entity\User');
    }

    function it_sets_email()
    {
        $this->setEmail('bartosz.rychlicki@gmail.com')->shouldReturnAnInstanceOf('Wsh\CmsBundle\Entity\User');
        $this->getEmail()->shouldReturn('bartosz.rychlicki@gmail.com');
    }

    function it_should_have_blank_email_at_default()
    {
        $this->getEmail()->shouldReturn(null);
    }
}
