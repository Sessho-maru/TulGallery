# About
このSPAアプリケーションはシンプルなイメージギャラリーです。</br>
基本的なイメージフォーマット、例えば _.jpg, .png ..._ だけじゃなく</br>
動くイメージフォーマット、_.gif_　と　動画 _.webm_ ファイルフォーマットもアップロード出来ます。

# Detail
###  * 重要 _route_ 一覧
| Method | URI           | Controller                       | View               | Auth Middleware |
|--------|---------------|----------------------------------|--------------------|:-----------------:|
| POST   | register      | Auth\RegisterController@register | register.blade.php |        -        |
| POST   | login         | Auth\LoginController@login       | login.blade.php    |        -        |
| GET    | api/imgs      | ImagesController@index           | ImageIndex.vue     |        X        |
| GET    | api/imgs/{id} | ImagesController@show            | ImageShow.vue      |        X        |
| POST    | api/imgs/{id} | ImagesController@store           | ImageCreate.vue    |        O        |
| PATCH  | api/imgs/{id} | ImagesController@update          | ImageEdit.vue      | O               |
| DELETE | api/imgs/{id} | ImagesController@destroy         | -                  | O               |
| POST   | api/search    | SearchController@index           |          -         |        X        |

このWEBアプリケーションはイメージの掲載(_ImagesController@store_)、修正(_ImagesController@update_)、削除(_ImagesController@destroy_)</br>
を行う為にはログインが必要です。</br>
その他の _controller_ は guest ユーザーでも実行出来ますが、guest ユーザーが上記の _controller_ を実行しようとしたら</br>
**/login** にURLが _redirect_ され、ログインページに移動されます。

### 0 ) Login

`Auth\LoginController` _controller_ から `AuthenticatesUsers` _trait_ が定義されている</br>
[login( _**Request $request**_ )](https://github.com/Sessho-maru/TulGallery/blob/master/vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php#L31-L55) を呼び出します。

まず、ユーザー入力した [`username`](https://github.com/Sessho-maru/TulGallery/blob/a03eb7e8533f047549d040ec4c6f955676fb5f52/vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php#L145-L148), `password` の有効性をチェックします。
```php認証済みのユーザーかチェックします。
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
この時、 [credentials( _**Request $request**_ )](https://github.com/Sessho-maru/TulGallery/blob/master/vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php#L92-L95) から返却された 'username', 'password' を _key_ としてする _array_ が渡されます。</br>
認証が成功したら [setUser()](https://github.com/Sessho-maru/TulGallery/blob/master/vendor/laravel/framework/src/Illuminate/Auth/SessionGuard.php#L822-L831) を通じて、その _session_ の[認証されたユーザーをセットします](https://github.com/Sessho-maru/TulGallery/blob/master/vendor/laravel/framework/src/Illuminate/Auth/GuardHelpers.php#L13-L18)。</br>
そして、[**/imgs** に _redirect_ されます](https://github.com/Sessho-maru/TulGallery/blob/master/app/Http/Controllers/Auth/LoginController.php#L24-L29)。

<br/>

### 1 ) このウェブアプリは SPA です
このWEBアプリケーションの[ダッシュボードは `home.blade.php` です](https://github.com/Sessho-maru/TulGallery/blob/master/app/Http/Controllers/AppController.php#L19-L27)。</br>
`home.blade.php` では現在の _session_ 認証済みのユーザーをチェックします。
```blade
{{-- home.blade.php --}}
@extends('layouts.app')

@section('content')
    <?php $user = auth()->user(); ?>
    @if ($user)
        <App v-bind:user="{{ $user }}"></App>
    @else
        <App></App>
    @endif
@endsection
```
`$user` が _null_ とか _false_ ではないなら `$user` を _props_ として渡します。</br>

[`App.vue`](https://github.com/Sessho-maru/TulGallery/blob/master/resources/js/components/App.vue) _component_ には [_navigation_ 領域](https://github.com/Sessho-maru/TulGallery/blob/master/resources/js/components/App.vue#L4-L42)、格URL毎にマッチする _component_ を表示する [_router-view_ 領域](https://github.com/Sessho-maru/TulGallery/blob/master/resources/js/components/App.vue#L44-L54) が分けられています。</br>
`App.vue` の _Vue instance_ が _mount_ されたらまず、`mounted()` で guest ユーザーか認証済みのユーザーかチェックして</br>
後者の場合、`this.authedUser` を初期化します。
```javascript
mounted() {
    if (this.user === undefined)
    {
        console.log("Guest");
    }
    else
    {
        this.authedUser = this.user;
        console.log("User login as", this.authedUser);
    }
},
```