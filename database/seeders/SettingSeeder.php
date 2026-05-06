<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── Hero ──────────────────────────────────────────
            ['key' => 'hero_sub',         'label' => 'Sous-titre hero',     'group' => 'hero',    'type' => 'text',     'value' => 'TRUSTED DEALER, RENTAL'],
            ['key' => 'hero_title',       'label' => 'Titre hero',          'group' => 'hero',    'type' => 'text',     'value' => 'Premium Car Collection..'],
            ['key' => 'hero_desc',        'label' => 'Description hero',    'group' => 'hero',    'type' => 'textarea', 'value' => 'Car Is Where Early Adopters And Innovation Seekers Find Lively Imaginative Tech Before It Hits The Mainstream.'],
            ['key' => 'hero_btn_text',    'label' => 'Texte bouton hero',   'group' => 'hero',    'type' => 'text',     'value' => 'Go To Listing'],
            ['key' => 'hero_image',       'label' => 'Image de fond hero',  'group' => 'hero',    'type' => 'image',    'value' => 'images/voiture2.png'],
            ['key' => 'hero_price_label', 'label' => 'Label price card',    'group' => 'hero',    'type' => 'text',     'value' => 'Luxury Ford Car'],
            ['key' => 'hero_price_amount','label' => 'Prix price card',     'group' => 'hero',    'type' => 'text',     'value' => '13 000 000 Fr'],
            ['key' => 'hero_price_addr',  'label' => 'Adresse price card',  'group' => 'hero',    'type' => 'text',     'value' => '1421 San Pedro St, Los Angeles, CA'],

            // ── Contact ───────────────────────────────────────
            ['key' => 'contact_phone1',   'label' => 'Téléphone 1',         'group' => 'contact', 'type' => 'text',     'value' => '+221 78 777 66 55'],
            ['key' => 'contact_phone2',   'label' => 'Téléphone 2',         'group' => 'contact', 'type' => 'text',     'value' => '+221 77 302 11 01'],
            ['key' => 'contact_email1',   'label' => 'Email 1',             'group' => 'contact', 'type' => 'text',     'value' => 'karim.ndiaye@gmail.com'],
            ['key' => 'contact_email2',   'label' => 'Email 2',             'group' => 'contact', 'type' => 'text',     'value' => 'abkauto@gmail.com'],
            ['key' => 'contact_address',  'label' => 'Adresse',             'group' => 'contact', 'type' => 'text',     'value' => 'Dakar Foire, Sénégal'],
            ['key' => 'contact_hours',    'label' => 'Horaires',            'group' => 'contact', 'type' => 'text',     'value' => 'Lun – Sam : 8h00 – 18h00'],
            ['key' => 'whatsapp_number',  'label' => 'Numéro WhatsApp',     'group' => 'contact', 'type' => 'text',     'value' => '221787776655'],

            // ── Réseaux sociaux ───────────────────────────────
            ['key' => 'social_facebook',  'label' => 'URL Facebook',        'group' => 'social',  'type' => 'url',      'value' => '#'],
            ['key' => 'social_instagram', 'label' => 'URL Instagram',       'group' => 'social',  'type' => 'url',      'value' => '#'],
            ['key' => 'social_tiktok',    'label' => 'URL TikTok',          'group' => 'social',  'type' => 'url',      'value' => '#'],
            ['key' => 'social_twitter',   'label' => 'URL Twitter/X',       'group' => 'social',  'type' => 'url',      'value' => '#'],

            // ── Best sellers ──────────────────────────────────
            ['key' => 'bestseller_title', 'label' => 'Titre best sellers',  'group' => 'bestseller', 'type' => 'text',  'value' => 'Obtenez le Meilleur Prix Pour Votre Voiture'],
            ['key' => 'bestseller_desc',  'label' => 'Description',         'group' => 'bestseller', 'type' => 'textarea', 'value' => 'Nous nous engageons à offrir à nos clients un service exceptionnel.'],

            // ── Services ──────────────────────────────────────
            ['key' => 'service1_title',   'label' => 'Service 1 titre',     'group' => 'services','type' => 'text',     'value' => 'Échange de Voiture'],
            ['key' => 'service1_desc',    'label' => 'Service 1 description','group' => 'services','type' => 'textarea','value' => 'Échangez votre véhicule actuel contre un autre de notre catalogue.'],
            ['key' => 'service2_title',   'label' => 'Service 2 titre',     'group' => 'services','type' => 'text',     'value' => 'Personnalisation'],
            ['key' => 'service2_desc',    'label' => 'Service 2 description','group' => 'services','type' => 'textarea','value' => 'Changement de couleur, carrosserie, intérieur et pièces mécaniques.'],
            ['key' => 'service3_title',   'label' => 'Service 3 titre',     'group' => 'services','type' => 'text',     'value' => 'Vente de Pièces Détachées'],
            ['key' => 'service3_desc',    'label' => 'Service 3 description','group' => 'services','type' => 'textarea','value' => 'Large stock de pièces d\'origine pour toutes les marques.'],
            ['key' => 'service4_title',   'label' => 'Service 4 titre',     'group' => 'services','type' => 'text',     'value' => 'Lavage Général'],
            ['key' => 'service4_desc',    'label' => 'Service 4 description','group' => 'services','type' => 'textarea','value' => 'Nettoyage intérieur et extérieur complet, polish et traitement céramique.'],

            // ── Général ───────────────────────────────────────
            ['key' => 'site_name',        'label' => 'Nom du site',         'group' => 'general', 'type' => 'text',     'value' => 'ABK AUTO'],
            ['key' => 'site_tagline',     'label' => 'Slogan',              'group' => 'general', 'type' => 'text',     'value' => 'Premium Car Rental'],
            ['key' => 'footer_copyright', 'label' => 'Copyright footer',    'group' => 'general', 'type' => 'text',     'value' => '2024 ABK AUTO Premium Car Rental. All rights reserved.'],
        
            // Best sellers — images
            ['key' => 'bestseller_image1', 'label' => 'Best Seller Image 1', 'group' => 'bestseller', 'type' => 'image', 'value' => ''],
            ['key' => 'bestseller_image2', 'label' => 'Best Seller Image 2', 'group' => 'bestseller', 'type' => 'image', 'value' => ''],
            ['key' => 'bestseller_image3', 'label' => 'Best Seller Image 3', 'group' => 'bestseller', 'type' => 'image', 'value' => ''],
            ['key' => 'bestseller_name1',  'label' => 'Best Seller Nom 1',   'group' => 'bestseller', 'type' => 'text',  'value' => ''],
            ['key' => 'bestseller_name2',  'label' => 'Best Seller Nom 2',   'group' => 'bestseller', 'type' => 'text',  'value' => ''],
            ['key' => 'bestseller_name3',  'label' => 'Best Seller Nom 3',   'group' => 'bestseller', 'type' => 'text',  'value' => ''],
            ['key' => 'bestseller_price1', 'label' => 'Best Seller Prix 1',  'group' => 'bestseller', 'type' => 'text',  'value' => ''],
            ['key' => 'bestseller_price2', 'label' => 'Best Seller Prix 2',  'group' => 'bestseller', 'type' => 'text',  'value' => ''],
            ['key' => 'bestseller_price3', 'label' => 'Best Seller Prix 3',  'group' => 'bestseller', 'type' => 'text',  'value' => ''],
            ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}