<?php

namespace App\Http\Controllers;
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
}
