<?

namespace Controllers;

class MainController extends Controller {

	public function underscoreTemplateAction() {
		$pathName = $this->app->getPathRequest('pathName');
		$templateName = $this->app->getPathRequest('templateName');
		if($pathName) {
			$this->includeTemplate("underscore/$pathName/$templateName.php");
		} else {
			$this->includeTemplate("underscore/$templateName.php");
		}
	}
}