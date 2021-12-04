# About
このSPAアプリケーションはシンプルなイメージギャラリーです。<br/>
基本的なイメージフォーマット、例えば _.jpg, .png ..._ だけじゃなく</br>
動くイメージフォーマット、_.gif_　と　動画 _.webm_ ファイルフォーマットもアップロード出来ます。

# Detail
###  * 重要 _route_ 一覧
| Method | URI           | Controller                       | View               |
|--------|---------------|----------------------------------|--------------------|
| POST   | register      | Auth\RegisterController@register | register.blade.php |
| POST   | login         | Auth\LoginController@login       | login.blade.php    |
| GET    | api/imgs      | ImagesController@index           | ImageIndex.vue     |
| GET    | api/imgs/{id} | ImagesController@show            | ImageShow.vue      |
| GET    | api/imgs/{id} | ImagesController@store           | ImageCreate.vue    |
| POST   | api/search    | SearchController@index           |          -         |

`Auth\LoginController` _controller_ から `AuthenticatesUsers` _trait_ に定義されている</br>
[login( _**Request $request**_ )](https://github.com/Sessho-maru/TulGallery/blob/master/vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php#L31-L55) を呼び出します。

まず、ユーザー入力した [`username`](https://github.com/Sessho-maru/TulGallery/blob/master/vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php#L68), `password` の有効性をチェックします。
```php
protected function validateLogin(Request $request)
{
    $request->validate([
        $this->username() => 'required|string',
        'password' => 'required|string',
    ]);
}
```
成功したら attemptLogin( _**Request $request**_ ) で
```php
protected function attemptLogin(Request $request)
{
    return $this->guard()->attempt(
        $this->credentials($request), $request->filled('remember')
    );
}
```
`Auth::guard` の _method_ [attempt( _**array $credentials, bool $remember**_)](https://github.com/Sessho-maru/TulGallery/blob/master/vendor/laravel/framework/src/Illuminate/Auth/SessionGuard.php#L353-L374) を実行します。</br>
この時、 [credentials( _**Request $request**_ )](https://github.com/Sessho-maru/TulGallery/blob/master/vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php#L92-L95) から返却された `username`, `password` を _key_ としてする _array_ が渡されます。</br>
認証が成功したら [setUser()](https://github.com/Sessho-maru/TulGallery/blob/master/vendor/laravel/framework/src/Illuminate/Auth/SessionGuard.php#L822-L831) を通じて、そのセッション[認証されたユーザーをセットします]()。
