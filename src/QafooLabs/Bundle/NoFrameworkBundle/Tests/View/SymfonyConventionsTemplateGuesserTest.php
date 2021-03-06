<?php

namespace QafooLabs\Bundle\NoFrameworkBundle\Tests\View;

use QafooLabs\Bundle\NoFrameworkBundle\View\SymfonyConventionsTemplateGuesser;

class SymfonyConventionsTemplateGuesserTest extends \PHPUnit_Framework_TestCase
{
    private $guesser;

    public function setUp()
    {
        $this->bundleLocation = \Phake::mock('QafooLabs\Bundle\NoFrameworkBundle\View\BundleLocation');
        $this->parser = \Phake::mock('QafooLabs\Bundle\NoFrameworkBundle\Controller\QafooControllerNameParser');

        $this->guesser = new SymfonyConventionsTemplateGuesser(
            $this->bundleLocation,
            $this->parser
        );
    }

    /**
     * @test
     */
    public function it_converts_controller_to_template_reference()
    {
        \Phake::when($this->bundleLocation)->locationFor('Controller\\FooController')->thenReturn('Bundle');

        $this->assertEquals(
            'Bundle:Foo:bar.html.twig',
            $this->guesser->guessControllerTemplateName('Controller\\FooController::barAction', null, 'html', 'twig')
        );
    }

    /**
     * @test
     */
    public function it_uses_parser_when_converting_non_callable_controller_to_template_reference()
    {
        \Phake::when($this->parser)->parse('foo:barAction')->thenReturn('Controller\\FooController::barAction');
        \Phake::when($this->bundleLocation)->locationFor('Controller\\FooController')->thenReturn('Bundle');

        $this->assertEquals(
            'Bundle:Foo:bar.html.twig',
            $this->guesser->guessControllerTemplateName('Controller\\FooController::barAction', null, 'html', 'twig')
        );
    }

    /**
     * @test
     */
    public function it_uses_provided_action_name_as_overwrite()
    {
        \Phake::when($this->parser)->parse('foo:barAction')->thenReturn('Controller\\FooController::barAction');
        \Phake::when($this->bundleLocation)->locationFor('Controller\\FooController')->thenReturn('Bundle');

        $this->assertEquals(
            'Bundle:Foo:baz.html.twig',
            $this->guesser->guessControllerTemplateName('Controller\\FooController::barAction', 'baz', 'html', 'twig')
        );
    }
}
