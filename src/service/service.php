<?php
namespace Silexpack\Service;

class Service
{

	/**
	 * @var \Silex\Application
	 */
	private $app;

	/**
	 * Service constructor.
	 * @param \Silex\Application $app
	 */
	public function __construct(\Silex\Application $app)
	{
		$this->app = $app;
	}

	/**
	 * @param string $tableName
	 * @return mixed
	 */
	public function selectAll(string $tableName)
	{
		$tableName = $this->validateTableName($tableName);
		return $this->app['db']->fetchAll("SELECT * FROM $tableName");
	}

	/**
	 * @param string $tableName
	 * @param string $parameterName
	 * @param $parameterValue
	 * @param string $fetchMethod
	 * @return mixed
	 */
	public function selectAllWithParameter(string $tableName, string $parameterName, $parameterValue, string $fetchMethod)
	{
		$tableName = $this->validateTableName($tableName);
		return $this->app['db']->$fetchMethod("SELECT * FROM $tableName WHERE $parameterName='$parameterValue'");
	}

	/**
	 * @param $tableName
	 * @return string
	 */
	private function validateTableName($tableName)
	{
		$tableName = strtolower($tableName);
		$tableName = trim($tableName);
		return $tableName;
	}


//	public function queryBuilder(){
//		$qb = new \stdClass();
//	}
}