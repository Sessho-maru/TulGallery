# About
この SPA Webアプリ はイメージギャラリーでございます。</br>
_.jpg, .png ..._ 等のイメージファイル</br>
_.gif_ とか Web 動画 _.webm_ ファイルもアップロード出来ます。

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
