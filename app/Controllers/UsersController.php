<?php

namespace App\Controllers;

use Rivulet\Controller;
use App\Models\Users;

class UsersController extends Controller {
    public function list() {
        return Users::all();
    }

    public function show($id) {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $user = Users::find($id);
        if (!$user) return json_error('User not found', 404);
        return $user;
    }

    public function store() {
        $data = $this->request->input();
        $this->validate($data, ['name' => 'required|string', 'email' => 'required|email', 'username' => 'required|string', 'password' => 'required|string']);
        $data['password'] = encrypt_password($data['password']);
        $user = new Users($data);
        $user->save();
        return $user;
    }

    public function modify($id) {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $user = Users::find($id);
        if (!$user) return json_error('User not found', 404);
        $data = $this->request->input();
        if (isset($data['password'])) $data['password'] = encrypt_password($data['password']);
        $user->fill($data);
        $user->save();
        return $user;
    }

    public function delete($id) {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $user = Users::find($id);
        if (!$user) return json_error('User not found', 404);
        $user->delete(true); // Soft delete
        return json_success('User soft deleted');
    }

    public function destroy($id) {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $user = Users::find($id);
        if (!$user) return json_error('User not found', 404);
        $user->delete(false); // Hard delete
        return json_success('User hard deleted');
    }
}