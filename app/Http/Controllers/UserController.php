<?php
/**
 * Created by PhpStorm.
 * User: tabares
 * Date: 11/10/2020
 * Time: 12:07 PM
 */

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController  extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Mostrar usuarios",
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar todos los usuarios."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function index()
    {
        $users = User::all();
        foreach ( $users as $user){
            if (count($user->roles) > 0) {
                $user['rol'] = $user->roles[0]->name;
            }
            else{
                $user['rol'] = 'user';
            }
        }

        return $users;
    }

    /**
     * Crear un nuevo Usuario
     * @param Request $request
     * @return static
     */
    public function create(Request $request){
        $parameters = $request->only('name', 'email', 'password', 'role' );

        $user = User::create([
            'email' => $parameters['email'],
            'name' => $parameters['name'],
            'password' => Hash::make($parameters['password'])
        ]);
        $role = Role::where('name',$parameters['role'])->first();
        $user->roles()->attach($role);
        $user['role'] = $parameters['role'];
        return $user;
    }

    /**
     * Asignar un Role a un Usuario
     * @param $userId
     * @param $roleId
     */
    public function attachUserRole($userId, $roleId){
        $user = User::find($userId);
        $roleId = Role::find($roleId);
        $user->roles()->attach($roleId);

        return $user;
    }

    /**
     * Obtener los roles de un Usuario
     * @param $userId
     * @return mixed
     */
    public function getUserRole($userId){
        return User::find($userId)->roles;
    }

    /**
     * Obtener la Lista de Roles
     * @return mixed
     */
    public function getAllRoles(){
        $roles = Role::all();
        return response()->json([$roles]);
    }

    /**
     * Obtener la Lista de Permisos
     * @return mixed
     */
    public function getAllPermissions(){
        $permissions =  Permission::all();
        return $this->response->array($permissions);
    }

    /**
     * Assignar un Permiso a un Rol
     * @param Request $request
     */
    public function attachPermission(Request $request){
        $parameters = $request->only('permission_id', 'role_id' );

        $permission_id= $parameters['permission_id'];
        $role_id = $parameters['role_id'];

        $role = Role::find($role_id);
        $permission = Permission::find($permission_id);

        $role->attachPermission($permission);

        return $this->response->created();

    }

    /**
     * Obtener Permisos de un Role
     * @param $role_id
     * @return mixed
     */
    public function getPermissions($role_id){
        $role = Role::find($role_id);

        //return $role->perms;
        return $this->response->array($role->perms);
    }

    /**
     * Obtener Datos de un Usuario
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }


    public function update($id, Request $request)
    {
        $parameters = $request->only('name', 'email', 'password', 'rol');

        $user = User::find($id);

        $user->name = $parameters['name'];
        $user->email = $parameters['email'];

        if ( ! $parameters['password'] == '')
        {
            $user->password = bcrypt($parameters['password']);
        }
        $user->save();

        $role = Role::where('name',$parameters['rol'])->first();
        $user->detachRoles($user->roles);
        $user->attachRole($role);

        return $user;
    }

    /**
     * Eliminar un Usuario siempre que no sea SUPERADMIN
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if(!$user){
            return $this->response->error("User not exist");
        }
        if(count($user->roles) > 0 && $user->roles[0]->name == 'superadmin'){
            return $this->response->error("User Can not be deleted");
        }

        $user->delete();
        return $user;
    }
    public function user_organization(){

        $id_user = 61;
        //$id_user = User::where('email',$request->email)->first();
        $id_org = DB::table('general.organizations_users as orgs')
            ->select('orgs.idorganization')
            ->where('iduser', $id_user)
            ->get();

        echo $id_org;

        $org = DB::table('general.organizations as org')
            ->select('org.name')
            ->where('idorganization',$id_org)
            ->first();

        return $org;


    }
}
