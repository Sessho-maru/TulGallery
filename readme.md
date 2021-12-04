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

`Auth\LoginController` _controler_ から `AuthenticatesUsers` _trait_ に定義されている
login( _**Request $request**_ )を呼び出します。