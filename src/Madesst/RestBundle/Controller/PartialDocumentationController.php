<?php
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 06.01.13
 * Time: 17:32
 * To change this template use File | Settings | File Templates.
 */
namespace Madesst\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Controller\ApiDocController;

class PartialDocumentationController extends ApiDocController
{
	/**
	 * @var string
	 */
	private $partial_documentation_method_name;

	public function __construct($container = null)
	{
		$this->container = $container;
	}

	public function partialDocumentationAction($resource)
	{
		$allExtractedDoc = $this->get('nelmio_api_doc.extractor.api_doc_extractor')->all();

		$extractedDoc = array();
		foreach ($allExtractedDoc as $doc) {
			if ($resource == $doc['resource']) {
				$extractedDoc[] = $doc;
			}
		}
		$htmlContent = $this->get('madesst_rest.formatter.partial_html_formatter')->format($extractedDoc);

		return new Response($htmlContent, 200, array('Content-Type' => 'text/html'));
	}

	public function setPartialDocumentationMethod($partial_documentation_method_name)
	{
		$this->partial_documentation_method_name = $partial_documentation_method_name;
	}

	public function getPartialDocumentationMethod()
	{
		return $this->partial_documentation_method_name;
	}
}
