<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Sample Project</h1>
    <br>
</p>

in this project user fill the multipart form.
The user is able to leave the registration on every step/view until he finished the whole registration successfully. Accordingly to this he should be redirected to the last opened step when he’s joining the process again(re-navigating to the webpage). So the state of already inserted data needs to be saved.


REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.6.0.



CONFIGURATION
-------------

### Database

you should run migration
`php yii migration`

**NOTES:**
- i consider 2 ways to save state of the user, cookie and session and you can switch between them from  config web.php file


`'components' => [

        'tempStorage' => [
            'class' => 'app\components\SessionStorage',
        ],
`

