<?php namespace Raconteur\Framework\Scenes;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Workbench\Package;

class SceneCreator {

	/**
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * Create a new scene creator instance.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem  $files
	 * @return void
	 */
	public function __construct(Filesystem $files)
	{
		$this->files = $files;
	}

	/**
	 * Create a new scene stub.
	 *
	 * @param  \Illuminate\Workbench\Package  $package
	 * @param  string  $path
	 * @param  bool    $plain
	 * @return string
	 */
	public function create(Package $package, $path)
	{
		$directory = $this->createDirectory($package, $path);

		return $directory;
	}

	/**
	 * Create a scene directory for the package.
	 *
	 * @param  \Illuminate\Workbench\Package  $package
	 * @param  string  $path
	 * @return string
	 */
	protected function createDirectory(Package $package, $path)
	{
		$fullPath = $path.'/'.$package->getFullName();

		// If the directory doesn't exist, we will go ahead and create the package
		// directory in the workbench location. We will use this entire package
		// name when creating the directory to avoid any potential conflicts.
		if ( ! $this->files->isDirectory($fullPath))
		{
			$this->files->makeDirectory($fullPath, 0777, true);

			return $fullPath;
		}

		throw new \InvalidArgumentException("Package exists.");
	}


}