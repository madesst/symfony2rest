<?php

namespace Madesst\RestBundle\Formatter;

use Symfony\Component\Templating\EngineInterface;
use Nelmio\ApiDocBundle\Formatter\HtmlFormatter;

class CustomHtmlFormatter extends HtmlFormatter
{
	/**
	 * @var boolean
	 */
	private $enableSandbox;

	/**
	 * @var string
	 */
	private $requestFormatMethod;

	/**
	 * @var array
	 */
	private $authentication;

	/**
	 * @param boolean $enableSandbox
	 */
	public function setEnableSandbox($enableSandbox)
	{
		$this->enableSandbox = $enableSandbox;
	}

	/**
	 * @param EngineInterface $engine
	 */
	public function setTemplatingEngine(EngineInterface $engine)
	{
		$this->engine = $engine;
	}

	/**
	 * @param string $method
	 */
	public function setRequestFormatMethod($method)
	{
		$this->requestFormatMethod = $method;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function renderOne(array $data)
	{
		return $this->engine->render('MadesstRestBundle::resource.html.twig', array_merge(
			array(
				'data'           => $data,
				'displayContent' => true,
			),
			$this->getGlobalVars()
		));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function render(array $collection)
	{
		return $this->engine->render('MadesstRestBundle::resources.html.twig', array_merge(
			array(
				'resources' => $collection,
			),
			$this->getGlobalVars()
		));
	}

	/**
	 * @return array
	 */
	private function getGlobalVars()
	{
		return array(
			'apiName'              => $this->apiName,
			'authentication'       => $this->authentication,
			'endpoint'             => $this->endpoint,
			'enableSandbox'        => $this->enableSandbox,
			'requestFormatMethod'  => $this->requestFormatMethod,
			'defaultRequestFormat' => $this->defaultRequestFormat,
			'date'                 => date(DATE_RFC822),
		);
	}
}
