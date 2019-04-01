<?

namespace Models;

class User extends Model {

	protected $id;
	protected $name;
	protected $email;
	protected $password;
	protected $token;
	protected $created;
	protected $updated;
	protected $role; // guest, admin
	protected $status; // active, disabled, pending
	protected $activation_code;
	protected $forgot_code;

	public function getWrapper() {
		return $this->app->getUserWrapper();
	}

	public function updateToken() {
		$prefix = time() . rand(1000, 9999);
		$token = md5(uniqid($prefix));
		$this->setProperties(['token' => $token]);
	}

	public function isAdmin() {
		return $this->get('role') === 'admin';
	}

	public function isGuest() {
		return $this->get('role') === 'guest';
	}

	public function isActive() {
		return $this->get('status') == 'active';
	}

	public function save() {
		if(!$this->id) {
			$this->created = date('Y-m-d H:i:s');
		}

		$this->updated = date('Y-m-d H:i:s');
		parent::save();
	}

	public function makePassword($passwordLength = 8) {
		$chars = [
			'q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m',
			'Q','W','E','R','T','Y','U','I','O','P','A','S','D','F','G','H','J','K','L','Z','X','C','V','B','N','M',
			'1','2','3','4','5','6','7','8','9','0','_'
		];

		$password = '';
		for($i = 0; $i < $passwordLength; $i++) {
			$pos = random_int(0, count($chars) - 1);
			$password .= $chars[$pos];
		}
		return $password;
	}

	public function makeActivationCode() {
		return md5( $this->makePassword(10) );
	}

	public function makeForgotCode() {
		return md5( $this->makePassword(16) );
	}

	public function getPasswordError() {
		$errorMessage = null;
		if( !preg_match('/^[\w]{8,16}$/', $this->get('password')) )  {
			$errorMessage = 'Пароль должен быть от 8 до 16 символов и может состоять только из букв, цифр и знака подчеркивания';
		}
		return $errorMessage;
	}

	public function getActivationLink() {
		return $this->app->protocol . $this->app->baseUrl . '/registration/confirm/' . $this->get('activation_code');
	}

	public function getForgotLink() {
		return $this->app->protocol . $this->app->baseUrl . '/change/password/' . $this->get('forgot_code');
	}

	public static function checkPhone($phone) {
		return preg_match('/^\d \(\d\d\d\) \d\d\d\-\d\d\-\d\d$/', $phone);
	}

	public static function checkEmail($email) {
		return preg_match('/^.+@.+\..+$/', $email);
	}

	public function getPublicData() {
		$userData = array_diff_key($this->getProperties(), [
			'password' => 1,
			'token' => 1,
			'created' => 1,
			'updated' => 1,
			'activation_code' => 1,
			'forgot_code' => 1
		]);

		return $userData;
	}
}