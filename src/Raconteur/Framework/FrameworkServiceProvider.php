<?php namespace Raconteur\Framework;

use Illuminate\Support\ServiceProvider;
use Raconteur\Framework\Console\InstallCommand;
use Raconteur\Framework\Console\SceneCommand;
use Raconteur\Framework\Scenes\SceneCreator;

class FrameworkServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('raconteur/framework');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->registerRaconteur();

		$this->registerCommands();

	}

	protected function registerRaconteur() {

		$this->app['raconteur'] = $this->app->share(function($app)
        {
            return new Raconteur;
        });

	}

	/**
	 * Register all of the reconteur commands.
	 *
	 * @return void
	 */
	protected function registerCommands()
	{
		$commands = array('install','scene');

		foreach ($commands as $command) {
			$this->{'register'.ucfirst($command).'Command'}();
			$this->commands('command.raconteur.'.$command);
		}
	}

	protected function registerInstallCommand()
	{
		$this->app['command.raconteur.install'] = $this->app->share(function($app) {
			return new InstallCommand;
		});
	}

	protected function registerSceneCommand()
	{
		$this->app['raconteur.scene.creator'] = $this->app->share(function($app)
		{
			return new SceneCreator($app['files']);
		});

		$this->app['command.raconteur.scene'] = $this->app->share(function($app) {

			$creator = $app['raconteur.scene.creator'];

			$packagePath = $app['path.base'].'/workbench';

			return new SceneCommand($creator,$packagePath);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('raconteur');
	}

}