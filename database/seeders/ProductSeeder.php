<?php

namespace Database\Seeders;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => "Razer DeathAdder Essential Gaming Mouse: 6400 DPI Optical Sensor - 5 Programmable Buttons - Mechanical Switches - Rubber Side Grips - Mercury White",
                'availableStock' => 154,
            ],
            [
                'name' => 'Logitech G502 HERO High Performance Wired Gaming Mouse, HERO 25K Sensor, 25,600 DPI, RGB, Adjustable Weights, 11 Programmable Buttons, On-Board Memory, PC / Mac',
                'availableStock' => 681,
            ],
            [
                'name' => "Logitech G203 Wired Gaming Mouse, 8,000 DPI, Rainbow Optical Effect LIGHTSYNC RGB, 6 Programmable Buttons, On-Board Memory, Screen Mapping, PC/Mac Computer and Laptop Compatible - Black",
                'availableStock' => 8615,
            ],
            [
                'name' => "Razer Viper Mini Ultralight Gaming Mouse: Fastest Gaming Switches - 8500 DPI Optical Sensor - Chroma RGB Underglow Lighting - 6 Programmable Buttons - Drag-Free Cord - Classic Black ",
                'availableStock' => 4813,
            ],
            [
                'name' => "REDRAGON ANUBIS WIRELESS/WIRED RGB MECHANICAL GAMING KEYBOARD WHITE (DUST-PROOF BROWN SWITCH) (K539W-RGB)",
                'availableStock' => 62,
            ],
            [
                'name' => "Logitech G305 LIGHTSPEED Wireless Gaming Mouse, Hero 12K Sensor, 12,000 DPI, Lightweight, 6 Programmable Buttons, 250h Battery Life, On-Board Memory, PC/Mac - Black",
                'availableStock' => 1755,
            ],
            [
                'name' => "Razer Mamba Wireless Gaming Mouse: 16,000 DPI Optical Sensor - Chroma RGB Lighting - 7 Programmable Buttons - Mechanical Switches - Up to 50 Hr Battery Life",
                'availableStock' => 999,
            ],
            [
                'name' => "SteelSeries Rival 3 Gaming Mouse - 8,500 CPI TrueMove Core Optical Sensor - 6 Programmable Buttons - Split Trigger Buttons - Brilliant Prism RGB Lighting",
                'availableStock' => 359,
            ],
            [
                'name' => "SteelSeries Apex 3 RGB Gaming Keyboard - 10-Zone RGB Illumination - IP32 Water Resistant - Premium Magnetic Wrist Rest (Whisper Quiet Gaming Switch)",
                'availableStock' => 336,
            ],
            [
                'name' => "CORSAIR K55 RGB PRO - Dynamic RGB Backlighting - Six Macro Keys with Elgato Stream Deck Software Integration - IP42 Dust and Spill Resistant - Detachable Palm Rest - Dedicated Media and Volume Keys",
                'availableStock' => 35,
            ],
            [
                'name' => "Razer Cynosa Chroma Gaming Keyboard: Individually Backlit RGB Keys - Spill-Resistant Design - Programmable Macro Functionality - Quiet & Cushioned",
                'availableStock' => 8,
            ],
            [
                'name' => "ROYAL KLUDGE RK84PRO TRI-MODE RGB 84 KEYS HOT SWAPPABLE MECHANICAL KEYBOARD WHITE (RED SWITCH)",
                'availableStock' => 231,
            ],
            [
                'name' => "ROYAL KLUDGE G87 DUAL-MODE RGB 87 KEYS MECHANICAL KEYBOARD BLACK (BLUE SWITCH)",
                'availableStock' => 553,
            ],
            [
                'name' => "LOGITECH G PRO MECHANICAL KEYBOARD LEAGUE OF LEGENDS EDITION",
                'availableStock' => 200,
            ],
            [
                'name' => "RAZER HUNTSMAN V2 OPTICAL GAMING KEYBOARD (CLICKY PURPLE SWITCH)",
                'availableStock' => 25,
            ],
        ];

        foreach($items as $item) {
            DB::table('products')->insert([
                'name' => $item['name'],
                'available_stock' => $item['availableStock'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

        }
    }
}
