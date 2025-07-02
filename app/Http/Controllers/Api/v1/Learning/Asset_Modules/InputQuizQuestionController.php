<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InputQuiz;
use App\Models\InputQuizQuestion;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class InputQuizQuestionController extends Controller
{
    public function index()
    {
        $questions = InputQuizQuestion::all();

        if ($questions->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada data soal yang tersedia.'
            ], 404);
        }

        return response()->json([
            'message' => 'Data soal berhasil diambil.',
            'data' => $questions
        ], 200);
    }
    
    public function showQuestionsByID($id_input_quizezz)
    {
        try {
            // Validasi apakah quiz exists
            $quiz = InputQuiz::find($id_input_quizezz);
            if (!$quiz) {
                return response()->json([
                    'message' => 'Quiz tidak ditemukan.'
                ], 404);
            }

            // Ambil semua questions untuk quiz ini
            $questions = InputQuizQuestion::where('id_input_quizezz', $id_input_quizezz)
                ->orderBy('created_at', 'asc')
                ->get();

            // Hitung total questions
            $totalQuestions = $questions->count();

            return response()->json([
                'message' => 'Data soal berhasil diambil.',
                'data' => [
                    'quiz_info' => [
                        'id_input_quizezz' => $id_input_quizezz,
                        'quiz_title' => $quiz->title ?? 'Judul Quiz',
                        'total_questions' => $totalQuestions
                    ],
                    'questions' => $questions
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request, $id_input_quizezz)
{
    try {
        // Validasi semua input
        $validated = $request->validate([
            'input_guideline.option_img' => 'required|array|size:4',
            'input_guideline.option_img.*.img' => 'required|file|image|max:2048',
            'input_guideline.option_img.*.value' => 'required|integer',

            'questions' => 'required|array|min:1',
            'questions.*.type' => 'required|string|in:image,text',
            'questions.*.answer' => 'required|integer',
            'questions.*.content' => 'required_if:questions.*.type,text|string|nullable',
            'questions.*.question' => 'required_if:questions.*.type,image|file|nullable',
        ]);

        // ✅ Upload gambar petunjuk (option_img)
        $guidelineImgs = [];
        foreach ($request->file('input_guideline.option_img') as $index => $imgItem) {
            // Ambil file dan value-nya
            $file = $imgItem['img'];
            $value = $request->input("input_guideline.option_img.$index.value");

            // Upload ke Cloudinary
            $upload = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'mathplay_gasing/options'
            ]);

            $guidelineImgs[] = [
                'img' => $upload->getSecurePath(),
                'value' => $value
            ];
        }

        // ✅ Simpan setiap pertanyaan
        $createdQuestions = [];
        foreach ($request->input('questions') as $index => $variant) {
            $type = $variant['type'];
            $answer = $variant['answer'];

            $desc = null;
            if ($type === 'text') {
                $desc = $variant['content'];
            } elseif ($type === 'image' && $request->hasFile("questions.$index.question")) {
                $file = $request->file("questions.$index.question");

                // Upload soal tipe image
                $upload = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'mathplay_gasing/quiz_questions'
                ]);
                $desc = $upload->getSecurePath();
            }

            $data = [
                'id_input_quizezz' => $id_input_quizezz,
                'type_question' => $type,
                'question_answer' => $answer,
                'description_question' => $desc,
                'input_guideline' => json_encode($guidelineImgs),
            ];

            $createdQuestions[] = InputQuizQuestion::create($data);
        }

        return response()->json([
            'message' => 'Soal berhasil ditambahkan.',
            'id_input_quizezz' => $id_input_quizezz,
            'total_questions' => count($createdQuestions),
            'data' => $createdQuestions
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

public function update(Request $request, $id)
{
    try {
        $question = InputQuizQuestion::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'type_question' => 'nullable|in:text,image',
            'description_question' => 'nullable|string',
            'question_answer' => 'nullable|string|max:255',

            'input_guideline.option_img' => 'nullable|array|size:4',
            'input_guideline.option_img.*.img' => 'required|file|image|max:2048',
            'input_guideline.option_img.*.value' => 'required|integer',
        ]);

        $data = [];

        // Jika jenis soal diubah
        if ($request->has('type_question')) {
            $data['type_question'] = $request->input('type_question');
        }

        // Jika isi soal diubah
        if ($request->has('description_question')) {
            $data['description_question'] = $request->input('description_question');
        }

        // Jika jawaban benar diubah
        if ($request->has('question_answer')) {
            $data['question_answer'] = $request->input('question_answer');
        }

        // Jika ingin mengganti semua gambar input_guideline
        if ($request->hasFile('input_guideline.option_img')) {
            $guidelineImgs = [];
            foreach ($request->file('input_guideline.option_img') as $index => $imgItem) {
                $file = $imgItem['img'] ?? null;
                $value = $request->input("input_guideline.option_img.$index.value");

                if ($file) {
                    $upload = Cloudinary::upload($file->getRealPath(), [
                        'folder' => 'mathplay_gasing/options'
                    ]);

                    $guidelineImgs[] = [
                        'img' => $upload->getSecurePath(),
                        'value' => $value
                    ];
                }
            }

            $data['input_guideline'] = json_encode($guidelineImgs);
        }

        // Proses update
        $question->update($data);

        return response()->json([
            'message' => 'Soal berhasil diperbarui.',
            'data' => $question->fresh()
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        $question = InputQuizQuestion::find($id);
        if (!$question) {
            return response()->json(['message' => 'Soal tidak ditemukan.'], 404);
        }

        $question->delete();
        return response()->json(['message' => 'Soal berhasil dihapus.'], 200);
    }

    private function handleFileUploads($request, $data)
    {
        $fileFields = [
            'question_quiz' => 'question_quiz_file',
            'option_1' => 'option_1_file',
            'option_2' => 'option_2_file',
            'option_3' => 'option_3_file',
            'option_4' => 'option_4_file'
        ];

        foreach ($fileFields as $field => $fileField) {
            if ($request->hasFile($fileField)) {
                $folder = $field === 'question_quiz' ? 'quiz_questions' : 'options';
                $uploadedUrl = Cloudinary::upload($request->file($fileField)->getRealPath(), [
                    'folder' => "mathplay_gasing/{$folder}"
                ])->getSecurePath();
                
                $data[$field] = $uploadedUrl;
            }
        }

        return $data;
    }

    private function validateFlexibleField($request, $fieldName, $required = false)
    {
        $hasFile = $request->hasFile($fieldName);
        $hasText = $request->has($fieldName) && !$hasFile;
        
        if ($required && !$hasFile && !$hasText) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => "Field {$fieldName} wajib diisi (berupa teks atau file gambar)."
            ]);
        }
        
        if ($hasFile) {
            // Validasi sebagai file gambar
            $request->validate([
                $fieldName => 'image|mimes:jpg,jpeg,png|max:2048'
            ]);
        } elseif ($hasText) {
            // Validasi sebagai teks
            $request->validate([
                $fieldName => 'string|max:1000' // Sesuaikan max length sesuai kebutuhan
            ]);
        }
    }

    private function handleFlexibleInputs($request, $data)
    {
        $flexibleFields = [
            'question_quiz' => 'quiz_questions',
            'option_1' => 'options',
            'option_2' => 'options', 
            'option_3' => 'options',
            'option_4' => 'options'
        ];
        
        foreach ($flexibleFields as $field => $folder) {
            if ($request->hasFile($field)) {
                // Jika input berupa file, upload ke Cloudinary
                $uploadedUrl = Cloudinary::upload(
                    $request->file($field)->getRealPath(), 
                    [
                        'folder' => "mathplay_gasing/{$folder}"
                    ]
                )->getSecurePath();
                
                $data[$field] = $uploadedUrl;
            } elseif ($request->has($field)) {
                // Jika input berupa teks, simpan langsung
                $data[$field] = $request->input($field);
            }
        }
        
        return $data;
    }
}