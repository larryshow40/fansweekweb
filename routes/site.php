<?php
$page = settingHelper('page_detail_prefix') ?? 'page';
$article = settingHelper('article_detail_prefix') ?? 'article';

Route::feeds();

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'isInstalledCheck']
    ],
    function () use($page, $article){

    	Route::group(['middleware' => ['XSS']], function () {
			Route::match(['get', 'post'], '/', 'HomeController@home')->name('home');
			Route::get('all-codes', 'HomeController@companyCodes')->name('all-codes');
			Route::get('code/{id}', 'HomeController@companyCodeShow')->name('code.show');
			Route::post('comment', 'HomeController@storeComment')->name('comments.store');
			Route::post('like-code', 'HomeController@likeCode');
			Route::post('dislike-code', 'HomeController@dislikeCode');
			Route::get('view-stats/{id}', 'HomeController@stats')->name('view.stats');
			Route::get('all-predictions', 'PredictionController@index')->name('predictions')->middleware('loginCheck', 'PremiumUser');
			Route::get('filter/predictions', 'PredictionController@filter')->name('filter.predictions');

			//Livescore 
			Route::get('livescores', 'LiveScoreController@index')->name('livescore');
			Route::get('test-predictions', 'PredictionController@index')->name('test.predictions');



			//start auth route
			Route::get('/login', 'UserController@showLoginForm')->name('site.login.form');
			Route::post('/login', 'UserController@login')->name('site.login');
			Route::get('/register', 'UserController@showRegistrationForm')->name('site.register.form');
			Route::post('/register', 'UserController@register')->name('site.register');
			Route::get('/logout', 'UserController@logout')->name('site.logout');
			Route::get('activation/{email}/{activationCode}','UserController@activation');
	        Route::get('sitemap','SitemapController@sitemap')->name('sitemap');
			Route::get('/forgot-password','UserController@forgotPassword')->name('forget-password');
			Route::post('/forgot-password','UserController@postForgotPassword')->name('do-forget-password');
			Route::get('reset/{email}/{activationCode}','UserController@resetPassword');
	        Route::post('reset/{email}/{activationCode}','UserController@PostResetPassword')->name('reset-password');
	        //end auth route

			Route::get('search', 'ArticleController@search')->name('article.search');
			Route::get('get-read-more-post-search', 'ArticleController@getReadMorePostSearch');

			Route::post('article/post/comment', 'CommentController@save')->name('article.save.comment');
			Route::post('article/post/comment/reply', 'CommentController@saveReply')->name('article.save.reply');
			Route::get('submit/news', 'ArticleController@submitNewsForm')->name('submit.news.form');
			Route::post('submit/news', 'ArticleController@saveNews')->name('submit.news.save');
			Route::post('site/send/message', 'PageController@sendMessage')->name('site.send.message');
			Route::post('poll-store', 'PollController@savePoll')->name('site.poll.store');
			Route::get('site-switch-langauge', 'CommentController@switchLanguage');
			Route::get('mode-change', 'CommentController@modeChange');
			Route::get('category/{slug}','ArticleController@postByCategory')->name('site.category');
			Route::get('sub-category/{slug}','ArticleController@postBySubCategory')->name('site.sub-category');
			Route::get('get-read-more-post-subcategory','ArticleController@getPostSubcategory');
			Route::get('get-read-more-post-category','ArticleController@getReadMorePostCategory');
			Route::get('tags/{slug}','ArticleController@postByTags')->name('site.tags');
			Route::get('get-read-more-post-tags','ArticleController@getReadMorePostTags');
			Route::get('get-read-more-post','ArticleController@getReadMorePost');
			Route::get('get-read-more-post-profile','ArticleController@getReadMorePostProfile');
            //		author panel
            Route::get('author-profile/{id}', 'AuthorController@profile')->name('site.author');

            Route::group(['middleware'=>['loginCheck']],function() {
                //      update profile
                Route::get('my-profile', 'AuthorController@myProfile')->name('site.profile');

				Route::get('subscription', 'SubscriptionController@index')->name('site.subscription.index');
				Route::post('subscribe', 'SubscriptionController@subscribe')->name('site.subscription.subscribe');
				Route::get('paystack/callback', 'SubscriptionController@handleCallback')->name('site.subscription.callback');

                Route::get('author-profile-edit', 'AuthorController@myProfileEdit')->name('site.profile.form');
                Route::post('author-profile-update', 'AuthorController@myProfileUpdate')->name('site.profile.save');

                Route::get('author-social', 'AuthorController@social')->name('site.author.socials');
                Route::post('author-social', 'AuthorController@socialUpdate')->name('site.author.socials.update');

                Route::get('author-preference', 'AuthorController@preference')->name('site.author.preference');
                Route::post('author-preference', 'AuthorController@preferenceUpdate')->name('site.author.preference.update');

                // author password
                Route::get('author-password', 'AuthorController@changePassword')->name('site.author.password');
            });
            //		article by dates
            Route::get('date/{date}', 'ArticleController@postByDate')->name('article.date');
			Route::get('get-read-more-post-date', 'ArticleController@getReadMorePostDate');

			//quiz-routes
			Route::get('get-quiz-answer-array', 'QuizController@getAnswerArray');
			Route::get('get-quiz-result-array', 'QuizController@getResultArray');

            Route::get('post/reaction', 'ReactionController@postReaction');

            Route::get('albums', 'PageController@imageAlbums')->name('image.albums');
            Route::get('album-gallery/{slug}', 'PageController@imageGallery')->name('album.gallery');

            Route::get('feeds', 'PageController@feed')->name('feeds');

			Route::get('404', function () {
			    return view('site.pages.404');
			});
			Route::get('403', function () {
			    return view('site.pages.403');
			});
		});
		Route::group(['prefix' => $article], function(){
		    Route::get('/{id}', 'ArticleController@show')->name('article.detail');
		});

		Route::group(['prefix' => $page], function(){
		    Route::get('/{slug}', 'PageController@page')->name('site.page');
		});

		// Social login
		Route::get('login/{provider}', 'SocialController@redirect');
		Route::get('login/{provider}/callback', 'SocialController@Callback');

	 }
);
