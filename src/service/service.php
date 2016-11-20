<?php
namespace Services;

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
	 * @param $tableName
	 * @return string
	 */
	private function validateTableName($tableName)
	{
		$tableName = strtolower($tableName);
		$tableName = trim($tableName);
		return $tableName;
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
	
	public function delete(string $tableName,  string $parameterName, $parameterValue){
		$tableName = $this->validateTableName($tableName);
		return $this->app['db']->delete($tableName, [$parameterName=>$parameterValue]);
	}

	public function uploadFiles(array $files, int $max_file_size, array $valid_formats, string $path) {

		$message = null;

		foreach ($files['name'] as $f => $name) {
			if ($files['error'][$f] == 4) {
				continue; // Skip file if any error found
			}
			if ($files['error'][$f] == 0) {
				if ($files['size'][$f] > $max_file_size) {
					$message = "$name is too large!.";
					continue; // Skip large files
				} elseif (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
					$message = "$name is not a valid format";
					continue; // Skip invalid file formats
				} else { // No error found! Move uploaded files
					if (move_uploaded_file($files["tmp_name"][$f], __DIR__ . $path . $name))
						$message[] = $path . $name;
				}
			}
		}

		return $message;
	}

	public function editData(string $tableName){

	}
}