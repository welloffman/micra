<?

namespace Wrappers;

use Models\User;

class UserWrapper extends MysqlWrapper {
	public function getName() {
		return 'user';
	}

	public function getObject() {
		return new User($this->app);
	}

	public function getCurrentUser() {
		$user = null;
		$token = $this->app->cookie('token');

		if($token) {
			$user = $this->findOne(['token' => $token]);
		}

		if(!$user) {
			$user = $this->getObject();
			$user->setProperties(['role' => 'guest']);
		}

		return $user;
	}

	public function findByInnAndPassword($inn, $password) {
		$user = null;
		$company = $this->app->getCompanyWrapper()->findOne(['inn' => $inn]);
		if($company) {
			$user = $this->findOne(['id' => $company->get('user_id'), 'password' => $password]);
		}
		
		return $user;
	}

	public function findByInn($inn) {
		$user = null;
		$company = $this->app->getCompanyWrapper()->findOne(['inn' => $inn]);
		if($company) {
			$user = $this->findOne(['id' => $company->get('user_id')]);
		}
		
		return $user;
	}

	public function getUsersStatus($users_ids) {
		$users_ids = array_map(function($id) {
			return (int)$id;
		}, $users_ids);

		$ids_string = implode(', ', $users_ids);

		$sql = "SELECT `id`, `status` FROM `user` WHERE `id` IN ($ids_string)";

		$result = array_map(function($item) {
			return ['id' => $item['id'], 'status' => $item['status']];
		}, $this->customQuery($sql, []));

		return $result;
	}
}