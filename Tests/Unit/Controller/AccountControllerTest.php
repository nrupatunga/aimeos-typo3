<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;


class AccountControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		\Aimeos\Aimeos\Base::getAimeos(); // initialize autoloader

		$context = new \Aimeos\MShop\Context\Item\Standard();
		$context->setView( new \Aimeos\MW\View\Standard() );

		$this->object = $this->getAccessibleMock( 'Aimeos\\Aimeos\\Controller\\AccountController', ['getContext'] );

		$uriBuilder = $this->getMockBuilder( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder' )->getMock();
		$request = $this->getMockBuilder( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Request' )->getMock();
		$response = $this->getMockBuilder( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Response' )
			->setMethods( ['getHeaders'] )
			->getMock();

		$response->expects( $this->once() )->method( 'getHeaders' )->will( $this->returnValue( [] ) );
		$this->object->expects( $this->once() )->method( 'getContext' )
			->will( $this->returnValue(  ) );


		$uriBuilder->setRequest( $request );

		if( method_exists( $response, 'setRequest' ) ) {
			$response->setRequest( $request );
		}

		$this->object->_set( 'uriBuilder', $uriBuilder );
		$this->object->_set( 'response', $response );
		$this->object->_set( 'request', $request );

		$this->object->_call( 'initializeAction' );
	}


	public function tearDown()
	{
		unset( $this->object );
	}


	/**
	 * @test
	 */
	public function downloadAction()
	{
		$name = '\\Aimeos\\Client\\Html\\Account\\Download\\Standard';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		\Aimeos\Client\Html\Account\Download\Factory::injectClient( $name, $client );
		$output = $this->object->downloadAction();
		\Aimeos\Client\Html\Account\Download\Factory::injectClient( $name, null );

		$this->assertEquals( '', $output );
	}


	/**
	 * @test
	 */
	public function historyAction()
	{
		$name = '\\Aimeos\\Client\\Html\\Account\\History\\Standard';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Aimeos\Client\Html\Account\History\Factory::injectClient( $name, $client );
		$output = $this->object->historyAction();
		\Aimeos\Client\Html\Account\History\Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}
}