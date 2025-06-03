<?php

namespace App\Http\Controllers\Api\v1\Learning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LearningSubject;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class LearningSubjectController extends Controller
{
    public function showLearningSubjects() {
        $subject = LearningSubject::all();
        return response() -> json([
            'message'=>'Data berhasil diambil',
            $subject
        ], 201);
    }

    public function addLearningSubjects(Request $request) {
        $request -> validate([
            'id_admins' => 'required|integer',
            'title_learning_subject' => 'required|string|max:100',
            'descripsion_learning_subject' => 'required|string|max:100' ,
            'img_card_subject' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'status'=> 'required|string|max:100',
        ]);

        $uplaodedImg = Cloudinary::upload($request->file('img_card_subject')->getRealPath()) -> getSecurePath();

        $subject = LearningSubject::create([
            'id_admins' => $request->id_admins,
            'title_learning_subject' => $request->title_learning_subject,
            'descripsion_learning_subject' => $request->descripsion_learning_subject,
            'img_card_subject' => $uplaodedImg, 
            'status' => $request->status, 
        ]);

        return response()->json([
            'message' => 'Pelajaran berhasil ditambahkan!',
            'data' => $subject,
        ], 201);
    }

    public function updateLearningSubject(Request $request, $id) {
        $subject = LearningSubject::find($id);
        if(!$subject) {
            return response() -> json(['message'=>'Data tidak ditemukan.']);
        }

        $request->validate([
            'id_admins' => 'nullable|integer',
            'title_learning_subject' => 'nullable|string|max:100',
            'descripsion_learning_subject' => 'nullable|string|max:100' ,
            'img_card_subject' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fields = ['id_admins','title_learning_subject','descripsion_learning_subject','status'];
        $data = [];

        foreach ($fields as $field) {
            if(!is_null($request->input($field))) {
                $data[$field] = $request->input($field);
            }
        }

        if($request->hasFile('img_card_subject')) {
            $uplaodedImg = Cloudinary::upload($request->file('img_card_subject')->getRealPath(), [
                'folder' => 'mathplay_gasing/learning'
            ])->getSecurepath();

            $data['img_card_subject'] = $uplaodedImg;
        }

        $subject->update($data);
        $subject->refresh();

        return response()->json([
            'message'=>'Proses update badge berhasil',
            'data' => $subject
        ], 201);

        if(!$data) {
            return response() -> json(['message'=>'Proses Update Gagal.']);
        }
    }

    public function deleteLearningSubject($id) {
        $subject = LearningSubject::findOrFail($id);

        $url = $subject -> img_card_subject;
        $publicId = $this->extractPublicId($url);

        if($publicId) {
            Cloudinary::destroy($publicId);
        }

        $subject->delete();

        return response()->json([
          'message' => 'Data pelajaran dan gambar berhasil dihapus.'  
        ], 200);
    }

    private function extractPublicId($url)
    {
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? '';

        $segments = explode('/', ltrim($path, '/'));
        $publicId = implode('/', array_slice($segments, 3)); 

        $publicId = preg_replace('/\.(jpg|jpeg|png|gif|webp)$/', '', $publicId);

        return $publicId;
    }

}
