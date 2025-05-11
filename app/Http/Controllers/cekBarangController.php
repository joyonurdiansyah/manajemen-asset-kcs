<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient as ClientImageAnnotatorClient;
use Google\Cloud\Vision\V1\Image;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;

class cekBarangController extends Controller
{
    public function cekbarang()
    {
        return view('tensor-camera.analisa-barang');
    }

    public function analyze(Request $request)
    {
        // Validasi input
        $request->validate([
            'image' => 'required|string',
        ]);
    
        try {
            $base64Image = $request->input('image');
            $imageContent = base64_decode($base64Image);
    
            $keyFilePath = storage_path('credentials/google-vision-credentials.json');
    
            // Inisialisasi ImageAnnotatorClient
            $imageAnnotator = new ClientImageAnnotatorClient([
                'credentials' => $keyFilePath
            ]);
    
            // Buat objek Image
            $image = (new Image())->setContent($imageContent);
    
            // Fitur yang ingin dianalisa
            $features = [
                (new Feature())->setType(Type::LABEL_DETECTION)->setMaxResults(10),
                (new Feature())->setType(Type::OBJECT_LOCALIZATION)->setMaxResults(5),
                (new Feature())->setType(Type::TEXT_DETECTION)->setMaxResults(5),
            ];
    
            // Buat request
            $annotateImageRequest = (new AnnotateImageRequest())
                ->setImage($image)
                ->setFeatures($features);
    
            // Buat BatchAnnotateImagesRequest
            $batchRequest = (new BatchAnnotateImagesRequest())
                ->setRequests([$annotateImageRequest]);
    
            // Kirim batch request
            $response = $imageAnnotator->batchAnnotateImages($batchRequest);
    
            // Ambil hasil response pertama
            $annotation = $response->getResponses()[0];
    
            // Siapkan hasil akhir
            $result = [
                'labels' => [],
                'objects' => [],
                'text' => []
            ];
    
            if ($annotation->getLabelAnnotations()) {
                foreach ($annotation->getLabelAnnotations() as $label) {
                    $result['labels'][] = [
                        'description' => $label->getDescription(),
                        'score' => $label->getScore()
                    ];
                }
            }
    
            if ($annotation->getLocalizedObjectAnnotations()) {
                foreach ($annotation->getLocalizedObjectAnnotations() as $object) {
                    $result['objects'][] = [
                        'name' => $object->getName(),
                        'score' => $object->getScore()
                    ];
                }
            }
    
            if ($annotation->getTextAnnotations()) {
                foreach ($annotation->getTextAnnotations() as $text) {
                    $result['text'][] = [
                        'description' => $text->getDescription(),
                        'locale' => $text->getLocale() ?? null
                    ];
                }
            }
    
            $imageAnnotator->close();
    
            return response()->json($result);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Google Vision API Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
