<?php

use Illuminate\Support\Facades\Route;
use App\Models\{
    Course,
    Image,
    Module,
    User,
    Preference,
    Permission
};

Route::get('/one-to-polymorphic', function(){
    $user = User::first();

    $user->image()->save(
        new Image(['path' => 'path/image.png'])
    );

    dd($user->image);
});

Route::get('/many-to-many-pivot', function(){

    $user = User::with('permissions')->find(1);
    //$user->permissions()->detach([2]);
    // $user->permissions()->attach([
    //     1 => ['active' => false],
    //     2 => ['active' => false],
    // ]);
    
    echo "{$user->name}<br>";
    foreach ($user->permissions as $permission) {
        echo "{$permission->name} - {$permission->pivot->active}<br>";
    }


});

Route::get('/many-to-many', function(){
    //Permission::create(['name' => 'menu_02']);
     $user = User::with('permissions')->find(1);
    
     $permission = Permission::find(1);
     //$user->permissions()->save($permission);
    //  $user->permissions()->saveMany([
    //      Permission::find(1),
    //      Permission::find(2),
    //  ]);

   /// $user->permissions()->attach([2]);
   $user->permissions()->detach([2]);

     $user->refresh();;

     dd($user->permissions);
});



Route::get('one-to-one', function(){
    $user = User::with('preference')->find(2);
    $data = [
        'backround_color' => '#fff'
    ];
    if($user->preference){
        $user->preference()->update($data);   
    }else{
        //$user->preference()->create($data);  
        $preference = new Preference($data) ; 
        $user->preference()->save($preference);    
    }
    $user->refresh();
    var_dump($user->preference);
    
    $user->preference->delete();
    $user->refresh();
    dd($user->preference);
});

Route::get('/one-to-many', function(){
    //$course = Course::create(['name' => 'Curso de laravel']);
    $course = Course::with('modules.lessons')->first();

    echo $course->name;
    echo '<br>';
    foreach ($course->modules as $module) {
        echo "modulo {$module->name} <br>";
        foreach ($module->lessons as $lession) {
            echo "Aula {$lession->name} <br>";
        }
    }
    dd( $course);
    $data = [
        'name' => 'Modulo x2',
    ];
    $course->modules()->create($data);
    //Module::find(2)->update();
   
    $modules = $course->modules;

    dd($modules);    


});

Route::get('/', function () {
    return view('welcome');
});
