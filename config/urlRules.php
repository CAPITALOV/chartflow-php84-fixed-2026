<?php

return [
    // '/'                                     => 'landing/index',
    '/'                                     => 'site/about',
    'test'                                  => 'site/test',
    'contact'                               => 'site/contact',
    'service'                               => 'site/service',
    'contacts'                              => 'site/contacts',
    'price'                                 => 'site/price',
    'captcha'                               => 'site/captcha',
    'time'                                  => 'site/time',
    'log'                                   => 'site/log',
    'logDb'                                 => 'site/log_db',
    'referal'                               => 'site/referal',
    'cap'                                   => 'site/cap',

    'about'                                 => 'site/about',

    'password/recover'                      => 'auth/password_recover',
    'password/recover/activate/<code:\\w+>' => 'auth/password_recover_activate',
    'changeEmail/activate/<code:\\w+>'      => 'auth/change_email_activate',

    'registration'                          => 'auth/registration',
    'registration/<code:\\w+>'              => 'auth/registration_referal',
    'registrationActivate/<code:\\w+>'      => 'auth/registration_activate',
    'login'                                 => 'auth/login',
    'loginAjax'                             => 'auth/login_ajax',
    'logout'                                => 'auth/logout',
    'auth'                                  => 'auth/auth',

    // superadmin
    'admin/news'                            => 'superadmin_news/index',
    'admin/news/add'                        => 'superadmin_news/add',
    'admin/news/<id:\\d+>/edit'             => 'superadmin_news/edit',
    'admin/news/<id:\d+>/delete'           => 'superadmin_news/delete',

    // superadmin
    'admin'                                 => 'superadmin/index',
    'admin/login'                           => 'superadmin/login',
    'admin/referal'                         => 'superadmin/referal',
    'admin/referal/delete'                  => 'superadmin/referal_delete',
    'admin/calc'                            => 'superadmin/calc',
    'admin/userStocks'                      => 'superadmin/users_stock',
    'admin/users'                           => 'superadmin/users',
    'admin/stock/calc'                      => 'superadmin/stock_calc',
    'admin/stock/calc/activate'             => 'superadmin/stock_calc_activate',

    'requests'                              => 'superadmin_requests/index',
    'requests/activate/<hash:\\w+>'         => 'superadmin_requests/activate',
    'requests/activate'                     => 'superadmin_requests/activate_ajax',
    'requests/delete'                       => 'superadmin_requests/delete_ajax',


    'stock/<id:\\d+>'                       => 'site/stock',

    // stock
    'stock'                                 => 'superadmin_stock/index',
    'stock/updateCode'                      => 'superadmin_stock/update_code',
    'stock/add'                             => 'superadmin_stock/add',
    'stock/toggle'                          => 'superadmin_stock/toggle',
    'stock/<id:\\d+>/edit'                  => 'superadmin_stock/edit',
    'stock/<id:\\d+>/import'                => 'superadmin_stock/import',
    'stock/<id:\\d+>/importKurs'            => 'superadmin_stock/import_kurs',
    'stock/<id:\\d+>/delete'                => 'superadmin_stock/delete',
    'stock/<id:\\d+>/show/<color:\\w+>'     => 'superadmin_stock/show',
    'stock/<id:\\d+>/deletePrognosisRed'    => 'superadmin_stock/prognosis_delete_red',
    'stock/<id:\\d+>/deletePrognosisBlue'   => 'superadmin_stock/prognosis_delete_blue',
    'stock/<id:\\d+>/kurs/add'              => 'superadmin_stock/kurs_add',
    'stock/<id:\\d+>/kurs/edit'             => 'superadmin_stock/kurs_edit',
    'stock/<id:\\d+>/kurs/delete'           => 'superadmin_stock/kurs_delete',
    'stock/kurs/update'                     => 'superadmin_stock/kurs_update',
    'stock/<id:\\d+>/prognosis/add'         => 'superadmin_stock/prognosis_add',
    'stock/<id:\\d+>/prognosis/edit'        => 'superadmin_stock/prognosis_edit',
    'stock/prognosis/update'                => 'superadmin_stock/prognosis_update',
    'stock/<id:\\d+>/graph'                 => 'superadmin_stock/graph',
    'stock/<id:\\d+>/graph2'                => 'superadmin_stock/graph2',

    // designer
    'designer'                              => 'designer/index',
    'designer/landing'                      => 'designer/landing',

    // cabinet
    'cabinet'                               => 'cabinet/index',
    'cabinet/savePng'                       => 'cabinet/save_png',
    'stockList'                             => 'cabinet/stock_list',
    'stockList/<id:\\d+>'                   => 'cabinet/stock_item',
    'stockList2/<id:\\d+>'                  => 'cabinet/stock_item2',
    'stockList3/<id:\\d+>'                  => 'cabinet/stock_item3',
    'stockList/graphAjax'                   => 'cabinet/graph_ajax',
    'cabinet/changeEmail'                   => 'cabinet/change_email',

    'profile'                               => 'cabinet/profile',
    'passwordChange'                        => 'cabinet/profile_password_change',


    'searchStockAutocomplete'               => 'cabinet/search_stock_autocomplete',
    'cabinet/passwordChange'                => 'cabinet/password_change',
    'cabinet/profile'                       => 'cabinet/profile',
    'cabinet/profile/unLinkSocialNetWork'   => 'cabinet/profile_unlink_social_network',
    'cabinet/profile/subscribe'             => 'cabinet/profile_subscribe',

    'cabinet/wallet/add/<id:\\d+>'          => 'cabinet_wallet/add',

    'cabinet/wallet/sendMail'               => 'cabinet_wallet/send_mail',
    'cabinet/wallet/add1'                   => 'cabinet_wallet/add1',
    'cabinet/wallet/add1/<id:\\d+>/success' => 'cabinet_wallet/add1_success',
    'cabinet/wallet/add1/<id:\\d+>/fail'    => 'cabinet_wallet/add1_fail',
    'cabinet/wallet/add1/ajax'              => 'cabinet_wallet/add1_ajax',

    'cabinet/wallet/add2'                   => 'cabinet_wallet/add2',
    'cabinet/wallet/add2/ajax'              => 'cabinet_wallet/add2_ajax',
    'cabinet/wallet/add2/<id:\\d+>/success' => 'cabinet_wallet/add2_success',
    'cabinet/wallet/add2/<id:\\d+>/fail'    => 'cabinet_wallet/add2_fail',

    'cabinet/wallet/add/step1'              => 'cabinet_wallet/add_step1',

    'money/history'                         => 'money/history',

    'chat'                                  => 'cabinet_chat/index',
    'chat/send'                             => 'cabinet_chat/send',
    'chat/list'                             => 'superadmin_chat/index',
    'chat/user/<id:\\d+>'                   => 'superadmin_chat/user',
    'chat/getNewMessages'                   => 'cabinet_chat/get_new_messages',
    'chat/test'                             => 'cabinet_chat/test',

    'yandexMoney'                           => 'yandex_money/auth',
    'yandexMoney/test1'                     => 'yandex_money/test1',
    'yandexMoney/test2'                     => 'yandex_money/test2',

    '<controller>/<action>'                 => '<controller>/<action>',
];
