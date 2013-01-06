<?php
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 06.01.13
 * Time: 17:46
 * To change this template use File | Settings | File Templates.
 */
namespace Madesst\RestBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class AcceptHeaderListener
{
	/**
	 * Container
	 *
	 * @var Symfony\Component\DependencyInjection\ContainerInterface
	 */
	protected $container;

	protected $redirect_controller_method_name;
	protected $target_request_format;
	protected $controllers_list;
	protected $controllers_ignore_list;

	protected $current_route_name;

	public function __construct($container,
								$redirect_controller_method_name,
								$target_request_format = 'html',
								array $controllers_list = array(),
								array $controllers_ignore_list = array())
	{
		$this->container = $container;

		$this->redirect_controller_method_name = $redirect_controller_method_name;
		$this->target_request_format = $target_request_format;
		$this->controllers_list = $controllers_list;
		$this->controllers_ignore_list = $controllers_ignore_list;

		//Prevent stupid flow
		$this->controllers_ignore_list[] = $this->container->get('madesst_rest.documentation_controller');

		//Prevent web profiler disapper
		$this->controllers_ignore_list[] = 'Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController';
	}

	public function onKernelController(FilterControllerEvent $event)
	{
		if ($event->getRequestType() != HttpKernelInterface::MASTER_REQUEST) {
			return;
		}

		$this->current_route_name = $event->getRequest()->get('_route');

		$current_controller = $event->getController();

		/*
		 * $current_controller passed can be either a class or a Closure. This is not usual in Symfony2 but it may happen.
		 * If it is a class, it comes in array format, which first element is class name, and second - method name
		 */
		if (!is_array($current_controller)) {
			return;
		}

		$current_controller_class_name = get_class($current_controller[0]);

		if (count($this->controllers_list) > 0) {
			//If controllers_list is charged - we can rewrite current controller only in that controllers

			foreach ($this->controllers_list as $available_controller_name) {
				if ($current_controller_class_name == $available_controller_name) {
					foreach ($this->controllers_ignore_list as $ignored_controller_name) {
						if ($current_controller_class_name == $ignored_controller_name) {
							return;
						}
					}

					return $this->executeRewriteRoutine($event);
				}
			}
		} else {
			//Controllers_list is empty and we only look that current controller is available, not in ignore list

			foreach ($this->controllers_ignore_list as $ignored_controller_name) {
				if ($current_controller_class_name == $ignored_controller_name) {
					return;
				}
			}

			return $this->executeRewriteRoutine($event);
		}
	}

	/*
	 * Request from browser without Accept header, forwarding to documentation
	 */
	protected function executeRewriteRoutine(FilterControllerEvent $event)
	{
		if ($event->getRequest()->getRequestFormat() == $this->target_request_format) {
			return $event->setController(function(){
				$controller = $this->container->get('madesst_rest.documentation_controller');
				$method = $this->redirect_controller_method_name;
				return $controller->$method($this->current_route_name);
			});
		}
	}
}