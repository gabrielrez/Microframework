<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Kitten;

class KittensController extends Controller
{
    protected $kittenModel;

    public function __construct()
    {
        $this->kittenModel = new Kitten();
    }

    public function index()
    {
        $kittens = $this->kittenModel->all();
        return $this->response(200, $kittens);
    }

    public function show($id)
    {
        $kitten = $this->kittenModel->find($id);

        if (!$kitten) {
            return $this->response(404, ['error' => 'Kitten not found']);
        }

        return $this->response(200, $kitten);
    }

    public function store()
    {
        $this->kittenModel->save($this->getRequestBody());
        return $this->response(201, ['success' => 'Kitten created successfully']);
    }

    public function update($id)
    {
        $this->kittenModel->update($id, $this->getRequestBody());
        return $this->response(200, ['success' => 'Kitten updated successfully']);
    }

    public function destroy($id)
    {
        $kitten = $this->kittenModel->find($id);

        if (!$kitten) {
            return $this->response(404, ['error' => 'Kitten not found']);
        }

        $this->kittenModel->delete($id);
        return $this->response(200, ['success' => 'Kitten deleted successfully']);
    }
}
