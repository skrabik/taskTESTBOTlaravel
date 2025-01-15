<?php

Route::get('', ['as' => 'admin.dashboard', function () {
	$content = 'Управление пользователями бота';
	return AdminSection::view($content, 'Dashboard');
}]);

Route::get('mailing', ['as' => 'admin.mailing', function () {
    return AdminSection::view(view('admin-mailing'), 'Массовая рассылка');
}]);
