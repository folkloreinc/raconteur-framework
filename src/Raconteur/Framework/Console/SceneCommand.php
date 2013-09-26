<?php namespace Raconteur\Framework\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Workbench\Package;
use Illuminate\Workbench\PackageCreator;
use Raconteur\Framework\Scenes\SceneCreator;

class SceneCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'raconteur:scene';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new scene';

	/**
	 * The scene creator instance.
	 *
	 * @var \Illuminate\Database\Migrations\MigrationCreator
	 */
	protected $creator;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(SceneCreator $creator, $packagePath)
	{
		parent::__construct();

		$this->creator = $creator;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$workbench = $this->runCreator($this->buildPackage());

		$this->info('Scene created!');

		//$this->callComposerUpdate($workbench);
	}

	/**
	 * Run the package creator class for a given Package.
	 *
	 * @param  \Illuminate\Workbench\Package  $package
	 * @return string
	 */
	protected function runCreator($package)
	{
		$path = $this->laravel['path.base'].'/workbench';

		return $this->creator->create($package, $path);
	}

	/**
	 * Call the composer update routine on the path.
	 *
	 * @param  string  $path
	 * @return void
	 */
	protected function callComposerUpdate($path)
	{
		chdir($path);

		passthru('composer install --dev');
	}

	/**
	 * Build the package details from user input.
	 *
	 * @return \Illuminate\Workbench\Package
	 */
	protected function buildPackage()
	{
		$name = $this->argument('name');

		$config = $this->laravel['config']['workbench'];

		return new Package('raconteur', $name, $config['name'], $config['email']);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'The name of the scene'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}