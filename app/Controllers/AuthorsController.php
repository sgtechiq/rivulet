<?php
namespace App\Controllers;

use App\Models\Authors;
use Rivulet\Auth\Authentication;
use Rivulet\Controller;

class AuthorsController extends Controller
{
    public function addAuthor()
    {
        $data = $this->request->input();
        $this->validate($data, ['name' => 'required', 'email' => 'required|email', 'password' => 'required']);
        $data['password'] = PassEncrypt($data['password']);
        $author           = Authors::create($data);
        LogMessage('Author added: ' . $author->getAttribute('id'), 'info');
        return jsonSuccess($author);
    }

    public function editAuthor($id)
    {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $author = Authors::find($id);
        if (! $author) {
            return jsonError('Author not found', 404);
        }

        $data = $this->request->input();
        if (isset($data['password'])) {
            $data['password'] = PassEncrypt($data['password']);
        }

        $author->fill($data);
        $author->save();
        LogMessage('Author edited: ' . $id, 'info');
        return jsonSuccess($author);
    }

    public function listAuthors()
    {
        $authors = Authors::all();
        return jsonSuccess($authors);
    }

    public function getAuthorInfo($id)
    {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $author = Authors::find($id);
        if (! $author) {
            return jsonError('Author not found', 404);
        }

        return jsonSuccess($author);
    }

    public function deleteAuthor($id)
    {
        $this->validate(['id' => $id], ['id' => 'required|integer']);
        $author = Authors::find($id);
        if (! $author) {
            return jsonError('Author not found', 404);
        }

        $author->delete(false);
        LogMessage('Author deleted: ' . $id, 'warning');
        return jsonSuccess('Author deleted');
    }

    public function signupAuthor()
    {
        $data = $this->request->input();
        $this->validate($data, ['name' => 'required', 'email' => 'required|email', 'password' => 'required']);
        $data['password'] = PassEncrypt($data['password']);
        $author           = Authors::create($data);
        $token            = Authentication::generateToken($author->getAttribute('id'));
        return jsonSuccess(['token' => $token]);
    }

    public function loginAuthor()
    {
        $data = $this->request->input();
        $this->validate($data, ['email' => 'required|email', 'password' => 'required']);
        $author = Authors::where('email', $data['email'])->first();
        if (! $author || ! PassVerify($data['password'], $author->getAttribute('password'))) {
            return jsonError('Invalid credentials', 401);
        }
        $token = Authentication::generateToken($author->getAttribute('id'));
        return jsonSuccess(['token' => $token]);
    }
}
