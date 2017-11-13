<?

$app->routes = [
	'/' => '\controllers\MainController:indexAction:?',
	'/login' => 'controllers\MainController:loginAction:?',
	'/logout' => 'controllers\MainController:logoutAction',
	
	// '/editor' => '\controllers\MainController:indexAction',
	// '/category-editor' => '\controllers\MainController:indexAction',

	// '/get/files/count' => '\controllers\MainController:getFilesCountAction',
	// '/make/images' => '\controllers\MainController:makeImagesAction',
	// '/category/list' => '\controllers\MainController:categoryListAction',
	// '/category' => '\controllers\MainController:createCategoryAction',
	// '/category/<id>' => 'controllers\MainController:deleteCategoryAction',
	// '/images/list' => 'controllers\MainController:galleryListAction',
	// '/image/categories' => 'controllers\MainController:imageCategoriesAction',
	// '/image/category/add' => 'controllers\MainController:imageCategoryAddAction',
	// '/image/category/remove' => 'controllers\MainController:imageCategoryRemoveAction',
];