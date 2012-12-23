<?php
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 22.12.12
 * Time: 17:20
 * To change this template use File | Settings | File Templates.
 */
namespace Madesst\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Madesst\RestBundle\Propel\Product;
use Madesst\RestBundle\Propel\ProductQuery;
use Madesst\RestBundle\Form\Type\ProductType;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

use Madesst\RestBundle\Factory\ProductLinkFactory;
use Hateoas\Builder\LinkBuilder;
use Hateoas\Builder\ResourceBuilder;

class ProductController extends Controller
{
	public function AllAction()
	{
		$products = ProductQuery::create()->paginate($this->getRequest()->get('page', 1));

		return $this->processRenderView($products);
	}

	public function getAction(Product $product)
	{
		return $this->processRenderView($product);
	}

	public function newAction()
	{
		return $this->processForm(new Product());
	}

	public function editAction(Product $product)
	{
		return $this->processForm($product);
	}

	public function removeAction(Product $product)
	{
		$product->delete();
		return $this->processRenderView(null, 204);
	}

	private function processForm(Product $product)
	{
		$statusCode = $product->isNew() ? 201 : 204;

		$form = $this->createForm(new ProductType(), $product);
		$form->bind($this->getRequest());

		if($form->isValid()) {
			$product->save();

			$response = new Response();
			$response->setStatusCode($statusCode);
			$response->headers->set('Location',
				$this->generateUrl(
					'madesst_rest_product_get', array('id' => $product->getId()),
					true
				)
			);

			return $response;
		}

		return View::create($form, 400);
	}

	private function processRenderView($data = null, $statusCode = 200)
	{
		$resourceBuilder = new ResourceBuilder(
			ProductLinkFactory::retrieveFactory(),
			new LinkBuilder($this->get('router')));

		if($data instanceof \PropelModelPager)
		{
			$response = $resourceBuilder->createCollection($data, 'Madesst\RestBundle\Propel\Product');
		}
		elseif($data !== null)
		{
			$response = $resourceBuilder->create($data);
		}
		else
		{
			$response = null;
		}

		return View::create($response, $statusCode);
	}
}