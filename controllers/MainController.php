<?

namespace controllers;

use models\File;

class MainController extends Controller {
	public function indexAction() {
		if($this->app->isAuth()) {
			$this->render('main/index.php');
		} else {
			$this->render('main/login.php');
		}
	}

	public function pageNotFoundAction() {
		$this->render('main/404.php', ['path' => $this->app->path]);
	}

	public function loginAction() {
		$this->app->login();
		$this->app->redirect('/');
	}

	public function logoutAction() {
		$this->app->logout();
		$this->app->redirect('/');
	}

	
}