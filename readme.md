<h1>Access to Google Sheets from Yii2</h1>

Simple extension for getting list of user's spreadsheets and
open selected spreadsheet in IFRAME.

Example of using Google REST API and OAuth2 authorization.

<h2>Demo</h2>
<a href='http://sample.vorst.ru/google'>List of your google spredsheets</a>.

<h2>Installation</h2>

<h3>Register Google-project first</h3>

Follow the instructions - 
<a href='https://support.google.com/cloud/answer/6158849?hl=en&ref_topic=6262490'>
    Setting up OAuth 2.0
</a>.

<h3>Set up extension</h3>

<pre>
$ composer require sergmoro1/yii2-google-spreadsheet "dev-master"
</pre>

<h2>Usage</h2>

1.Copy model, controller and views from ./example to corresponding directories of your app.
Change methords and actions or leave them as is.

2.Define constants in params file (for ex. in frontend/config/params.php).

<pre>
&lt;?php
return [
    'clientId' =&gt; '***',
    'clientSecret' =&gt; '***',
    // dev (for ex.)
    'redirectUri' =&gt; 'http://localhost/yoursite/frontend/web/google/oauth',
    // production
    //'redirectUri' =&gt; 'http://yoursite/google/oauth',
];
</pre>

3.Declare Redirect Uri in your Google Project. For ex.
 
// dev<br>
http://localhost/yousite/frontend/web/google/oauth2

// production<br>
http://yoursiteDOTcom/google/oauth2

<h2>Result</h2>
Now you can view list of your spreadsheets, if you have them, by active Uri at the moment:

http://yoursite_or_localhost_path/google

