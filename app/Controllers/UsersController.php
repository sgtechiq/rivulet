<?php
namespace App\Controllers;

use App\Models\Users;
use Rivulet\Controller;

class UsersController extends Controller
{
    public function list()
    {
        return Users::all();
    }

    public function show($id)
    {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $user = Users::find($id);
        if (! $user) {
            return jsonError('User not found', 404);
        }

        return $user;
    }

    public function store()
    {
        $data = $this->request->input();
        $this->validate($data, ['name' => 'required|string', 'email' => 'required|email', 'username' => 'required|string', 'password' => 'required|string']);
        $data['password'] = PassEncrypt($data['password']);
        $user             = Users::create($data);
        return $user;

    }

    public function modify($id)
    {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $user = Users::find($id);
        if (! $user) {
            return jsonError('User not found', 404);
        }

        $data = $this->request->input();
        if (isset($data['password'])) {
            $data['password'] = PassEncrypt($data['password']);
        }

        $user->fill($data);
        $user->save();
        return $user;
    }

    public function delete($id)
    {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $user = Users::find($id);
        if (! $user) {
            return jsonError('User not found', 404);
        }

        $user->delete(true); // Soft delete
        return jsonSuccess('User soft deleted');
    }

    public function destroy($id)
    {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $user = Users::find($id);
        if (! $user) {
            return jsonError('User not found', 404);
        }

        $user->delete(false); // Hard delete
        return jsonSuccess('User hard deleted');
    }
}
