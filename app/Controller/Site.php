<?php
namespace Controller;

use Model\Worker;
use Model\Division;
use Model\Discipline;
use Model\Role;
use Model\User;
use Src\Request;
use Src\View;
use Src\Auth\Auth;


class Site
{

    public function hello(): string
    {
        $user = User::find($_SESSION['id']);
        return new View('site.hello', ['message' => 'Вы вошли в роль: ', 'user' => $user]);
    }

    public function signup(Request $request): string
    {   $roles = Role::all();
        if ($request->method === 'POST' && User::create($request->all())) {
            app()->route->redirect('/hello');
        }
        return new View('site.signup', ['roles'=>$roles]);
    }

    public function login(Request $request): string
    {
        //Если просто обращение к странице, то отобразить форму
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        //Если удалось аутентифицировать пользователя, то редирект
        if (Auth::attempt($request->all())) {
            app()->route->redirect('/hello');
        }
        //Если аутентификация не удалась, то сообщение об ошибке
        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function discipline(): string
    {
        $discipline = Discipline::all();
        return new View('site.discipline', [
            'discipline'=>$discipline,
        ]);
    }

    public function division(): string
    {
        $division = Division::all();
        return new View('site.division', [
            'division'=>$division,
        ]);
    }

    public function worker(): string
    {
        $worker = Worker::all();
        return new View('site.worker', [
            'worker'=>$worker,
        ]);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/login');
    }


}


