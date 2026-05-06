<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;



class CarService
{
    // ── GET ALL ──────────────────────────────────────────
    public function getAllCars()
    {
        try {
            return Car::with(['categorie', 'images'])->get();
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la récupération des véhicules : ' . $e->getMessage());
        }
    }

    // ── GET BY ID ─────────────────────────────────────────
    public function getCarById($id)
    {
        try {
            return Car::with(['categorie', 'images'])->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new \Exception('Véhicule introuvable.');
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la récupération du véhicule : ' . $e->getMessage());
        }
    }

    // ── CREATE ────────────────────────────────────────────
    public function createCar(array $data)
    {
        DB::beginTransaction();
        try {   
            // 1. Créer le véhicule
            $car = Car::create([
                'nom'          => $data['nom'],
                'category_id'  => $data['category_id'],
                'marque'       => $data['marque'],
                'model'        => $data['model'],
                'annee'        => $data['annee'],
                'prix'         => $data['prix'],
                'kilometrage'  => $data['kilometrage'],
                'carburant'    => $data['carburant'],
                'transmission' => $data['transmission'],
                'couleur'      => $data['couleur'],
                'description'  => $data['description'] ?? null,
                'status'       => $data['status']      ?? 'nouveau',
            ]);
                // Sauvegarder les images
                /*if (!empty($data['images']) && is_array($data['images'])) {
                    foreach ($data['images'] as $index => $imageFile) {
                        $path = $imageFile->store('cars', 'public');

                        $car->images()->create([
                            'chemin'        => $path,
                            'isPrimary' => $index===0, 
                            'dateCreation' => now(),
                            'altText' => $car->nom . " image " . ($index + 1),
                            'car_id'=>$car['id'],
                        ]);
                    }
                }*/

                    if (!empty($data['images']) && is_array($data['images'])) {
                    foreach ($data['images'] as $index => $imageFile) {
                        
                        // Upload sur Cloudinary
                        $result = Cloudinary::upload($imageFile->getRealPath(), [
                            'folder' => 'abk-auto/cars'
                        ]);

                        $car->images()->create([
                            'chemin'       => $result->getSecurePath(), // ← URL complète Cloudinary
                            'is_primary'   => $index === 0,
                            'dateCreation' => now(),
                            'altText'      => $car->nom . " image " . ($index + 1),
                            'car_id'       => $car['id'],
                        ]);
                    }
                }

            DB::commit();
            return $car->load(['categorie', 'images']);

        } catch (\Exception $e) {
           DB::rollBack();
            // Affiche le message exact de l'erreur dans le log
            Log::error('Erreur création véhicule', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Retourne une exception avec message clair
            throw new \Exception('Erreur lors de la création du véhicule : ' . $e->getMessage());
        }
    }

    // ── UPDATE ────────────────────────────────────────────
    public function updateCar(Car $car, array $data)
    {
        DB::beginTransaction();
        try {
            $car->update([
                'nom'          => $data['nom']          ?? $car->nom,
                'category_id'  => $data['category_id']  ?? $car->categorie_id,
                'marque'       => $data['marque']        ?? $car->marque,
                'model'        => $data['model']         ?? $car->model,
                'annee'        => $data['annee']         ?? $car->annee,
                'prix'         => $data['prix']          ?? $car->prix,
                'kilometrage'  => $data['kilometrage']   ?? $car->kilometrage,
                'carburant'    => $data['carburant']     ?? $car->carburant,
                'transmission' => $data['transmission']  ?? $car->transmission,
                'couleur'      => $data['couleur']       ?? $car->couleur,
                'description'  => $data['description']  ?? $car->description,
                'status'       => $data['status']        ?? $car->status,
            ]);

            // Ajouter de nouvelles images si présentes
           /* if (!empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $index => $imageFile) {

                    if (!$imageFile->isValid()) {
                        throw new \Exception("Image invalide à l'index {$index}.");
                    }

                    $path = $imageFile->store('cars', 'public');

                    if (!$path) {
                        throw new \Exception("Impossible de sauvegarder l'image.");
                    }

                    // Première image principale seulement si aucune n'existe
                    $isPrimary = $index === 0 && $car->images()->where('is_primary', true)->doesntExist();

                    $car->images()->create([
                        'url'        => asset('storage/' . $path),
                        'is_primary' => $isPrimary,
                    ]);
                }
            }*/

                // Ajouter de nouvelles images si présentes
            if (!empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $index => $imageFile) {

                    if (!$imageFile->isValid()) {
                        throw new \Exception("Image invalide à l'index {$index}.");
                    }

                    // Upload sur Cloudinary
                    $result = Cloudinary::upload($imageFile->getRealPath(), [
                        'folder' => 'abk-auto/cars'
                    ]);

                    // Première image principale seulement si aucune n'existe
                    $isPrimary = $index === 0 && $car->images()->where('is_primary', true)->doesntExist();

                    $car->images()->create([
                        'chemin'     => $result->getSecurePath(), // ← URL complète Cloudinary
                        'is_primary' => $isPrimary,
                    ]);
                }
            }

            DB::commit();
            return $car->load(['categorie', 'images']);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erreur lors de la mise à jour du véhicule : ' . $e->getMessage());
        }
    }

    // ── DELETE ────────────────────────────────────────────
    public function deleteCar(Car $car)
    {
        DB::beginTransaction();
        try {
            // Supprimer les fichiers physiques
           /* foreach ($car->images as $image) {
                $path = str_replace(asset('storage/'), '', $image->url);
                Storage::disk('public')->delete($path);
            }*/

                 foreach ($car->images as $image) {
            if (str_contains($image->chemin, 'cloudinary.com')) {
                $publicId = $this->extractPublicId($image->chemin);
                Cloudinary::destroy($publicId);
            } else {
                Storage::disk('public')->delete($image->chemin);
            }
        }

            $car->delete(); // cascade supprime les images en BDD

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erreur lors de la suppression du véhicule : ' . $e->getMessage());
        }
    }

    // ── DELETE IMAGE ──────────────────────────────────────
    public function deleteImage(CarImage $image)
    {
        try {
           /* $path = str_replace(asset('storage/'), '', $image->url);
            Storage::disk('public')->delete($path);*/
            if (str_contains($image->chemin, 'cloudinary.com')) {
            $publicId = $this->extractPublicId($image->chemin);
            Cloudinary::destroy($publicId);
        } else {
            Storage::disk('public')->delete($image->chemin);
        }
            $image->delete();
            return true;
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la suppression de l\'image : ' . $e->getMessage());
        }
    }

    // ── SET PRIMARY IMAGE ─────────────────────────────────
    public function setPrimaryImage(CarImage $image)
    {
        try {
            CarImage::where('car_id', $image->car_id)->update(['is_primary' => false]);
            $image->update(['is_primary' => true]);
            return $image;
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la définition de l\'image principale : ' . $e->getMessage());
        }
    }

    // ── EXTRACT PUBLIC ID ─────────────────────────────────
private function extractPublicId(string $url): string
{
    $path = parse_url($url, PHP_URL_PATH);
    preg_match('/\/image\/upload\/(?:v\d+\/)?(.+)\.\w+$/', $path, $matches);
    return $matches[1] ?? '';
}
}