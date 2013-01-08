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

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ProductController extends Controller
{
	/**
	 * This the documentation description of your method, it will appear
	 * on a specific pane. It will read all the text until the first
	 * annotation.
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Returns a collection of Product in native order",
	 *  filters={
	 *      {"name"="page", "dataType"="integer", "default"="1"},
	 *  },
	 *  statusCodes={
	 *			200="Returned when successfull"}
	 * )
	 * )
	 */
	public function AllAction()
	{
		$products = ProductQuery::create()->paginate($this->getRequest()->get('page', 1));

		return $this->processRenderView($products);
	}

	/**
	 * This the documentation description of your method, it will appear
	 * on a specific pane. It will read all the text until the first
	 * annotation.
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Returns a existing Product",
	 *  output="Madesst\RestBundle\Propel\Product",
	 *  statusCodes={
	 *			200="Returned when successfull",
	 * 			404="Returned when object not found"}
	 * )
	 * )
	 */
	public function getAction(Product $product)
	{
		return $this->processRenderView($product);
	}

	/**
	 * This the documentation description of your method, it will appear
	 * on a specific pane. It will read all the text until the first
	 * annotation.
	 *
	 * @ApiDoc(
	 *  description="Create a new Product",
	 *  input="Madesst\RestBundle\Form\Type\ProductType",
	 *  output="Madesst\RestBundle\Propel\Product",
	 *  statusCodes={
	 *			201="Returned when successfull with Location header",
	 *			204="Returned when object already exists",
	 *			400="Returned when input parameters are wrong"}
	 * )
	 */
	public function newAction()
	{
		return $this->processForm(new Product());
	}

	/**
	 * This the documentation description of your method, it will appear
	 * on a specific pane. It will read all the text until the first
	 * annotation.
	 *
	 * @ApiDoc(
	 *  description="Update an existing Product",
	 *  input="Madesst\RestBundle\Form\Type\ProductType",
	 *  output="Madesst\RestBundle\Propel\Product",
	 *  statusCodes={
	 *			204="Returned when successfull with Location header",
	 *			400="Returned when input parameters are wrong",
	 * 			404="Returned when object not found"}
	 * )
	 */
	public function editAction(Product $product)
	{
		return $this->processForm($product);
	}

	/**
	 * This the documentation description of your method, it will appear
	 * on a specific pane. It will read all the text until the first
	 * annotation.
	 *
	 * @ApiDoc(
	 *  resource=false,
	 *  description="Delete a given Product",
	 *  statusCodes={
	 *			204="Returned when successfull",
	 * 			404="Returned when object not found"}
	 * )
	 */
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

		if ($form->isValid()) {
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

		if ($data instanceof \PropelModelPager) {
			$response = $resourceBuilder->createCollection($data, 'Madesst\RestBundle\Propel\Product');
		} elseif ($data !== null) {
			$response = $resourceBuilder->create($data);
		} else {
			$response = null;
		}

		return View::create($response, $statusCode);
	}
}