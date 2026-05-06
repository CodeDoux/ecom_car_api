<?php


namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CarImageController extends Controller
{
    // GET /api/cars/{car}/images
    public function index(Car $car)
    {
        try {
            $images = $car->images;
            return response()->json($images);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);
        }
    }

    // POST /api/cars/{car}/images
    public function store(Request $request, Car $car)
    {
        try {
            $images = [];
            foreach ($request->file('images') as $index => $file) {
                $result = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'abk-auto/cars'
                ]);

                $isPrimary = $index === 0 && $car->images()->where('is_primary', true)->doesntExist();

                $images[] = $car->images()->create([
                    'chemin'     => $result->getSecurePath(),
                    'is_primary' => $isPrimary,
                ]);
            }

            return response()->json($images);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // PUT /api/car-images/{carImage}/primary
    public function setPrimary(CarImage $carImage)
    {
        try {
            CarImage::where('car_id', $carImage->car_id)
                    ->update(['is_primary' => false]);

            $carImage->update(['is_primary' => true]);

            return response()->json($carImage);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/car-images/{carImage}
    public function destroy(CarImage $carImage)
    {
        try {
            // Supprimer sur Cloudinary si URL Cloudinary
            if (str_contains($carImage->chemin, 'cloudinary.com')) {
                // Extraire le public_id depuis l'URL
                $publicId = $this->extractPublicId($carImage->chemin);
                Cloudinary::destroy($publicId);
            } else {
                // Ancienne image sur storage local
                \Storage::disk('public')->delete($carImage->chemin);
            }

            $carImage->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // Extraire le public_id depuis une URL Cloudinary
    private function extractPublicId(string $url): string
    {
        // URL: https://res.cloudinary.com/cloud/image/upload/v123/abk-auto/cars/xxx.jpg
        $path = parse_url($url, PHP_URL_PATH);
        // Retire /image/upload/vXXXX/ et l'extension
        preg_match('/\/image\/upload\/(?:v\d+\/)?(.+)\.\w+$/', $path, $matches);
        return $matches[1] ?? '';
    }
}

/*namespace App\Http\Controllers;
use App\Models\Car;

use Illuminate\Http\Request;

class CarImageController extends Controller
{
   // GET /api/cars/{car}/images
    public function index(Car $car)
    {
        try {
        $images = $car->images;
        return response()->json($images);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);
        }
    }

    // PUT /api/car-images/{carImage}/primary
    public function setPrimary(CarImage $carImage)
    {
        try {
            // Retirer l'image principale actuelle
            CarImage::where('car_id', $carImage->car_id)
                    ->update(['is_primary' => false]);

            // Définir la nouvelle image principale
            $carImage->update(['is_primary' => true]);

            return response()->json($carImage);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/car-images/{carImage}
    public function destroy(CarImage $carImage)
    {
        try {
            \Storage::disk('public')->delete($carImage->chemin);
            $carImage->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}*/
