<?php

namespace App\Http\Controllers;

use App\Plugin;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Charts\SampleChart;

class HomeController extends Controller
{
    protected $plugins = [
        'woocommerce'    => 'Woocommerce',
        'contact-form-7' => 'Contact form 7',
        'classic-editor' => 'Clasic editor',
        'wordpress-seo'  => 'Yoast SEO'
    ];

    public function index(Request $request)
    {
        $client = new Client();
        $plugins = Plugin::first();

        if (!$plugins) {
            foreach ($this->plugins as $key => $value) {
                $downloads = $client->request('POST', 'https://api.wordpress.org/stats/plugin/1.0/downloads.php?slug='. $key . '&limit=30');
                $downloads_data = json_decode((string) $downloads->getBody(), true);

                $active_installs = $client->request('POST', 'https://api.wordpress.org/stats/plugin/1.0/active-installs.php?slug='. $key . '&limit=30
                ');
                $active_installs_data = json_decode((string) $active_installs->getBody(), true);
                
                foreach ($downloads_data as $index => $val) {
                    $plugin = new Plugin();
                    $plugin->name = $value;
                    $plugin->downloaded = $val;
                    $plugin->date = $index;
                    $plugin->save();
                }
                
                foreach ($active_installs_data as $x => $y) {
                    $plugin = Plugin::where('name', $value)->where('date', $x)->first();
                    if ($plugin) {
                        $plugin->active_installed = $y * 100000;
                        $plugin->save();
                    }
                }
            }
        } else {
            $plugin = Plugin::where('date', now()->today()->toDateString())->first();
            if (!$plugin) {
                foreach ($this->plugins as $key => $value) {
                    $downloads = $client->request('POST', 'https://api.wordpress.org/stats/plugin/1.0/downloads.php?slug='. $key . '&limit=1');
                    $downloads_data = json_decode((string) $downloads->getBody(), true);
                    
                    $active_installs = $client->request('POST', 'https://api.wordpress.org/stats/plugin/1.0/active-installs.php?slug='. $key . '&limit=2
                        ');
                    $active_installs_data = json_decode((string) $active_installs->getBody(), true);
                
                    foreach ($downloads_data as $key => $data) {
                        $plugin = new Plugin();
                        $plugin->name = $value;
                        $plugin->downloaded = $data;
                        $plugin->date = $key;
                        $plugin->save();
                    }
                    
                    foreach ($active_installs_data as $a => $b) {
                        $plugin = Plugin::where('date', $a)->whereNotNull('active_installed')->first();

                        if (!$plugin) {
                            $plugin = Plugin::where('name', $value)->where('date', $a)->first();
                            $plugin->active_installed = $b * 100000;
                            $plugin->save();      
                        }
                    }
                }
            }
        }

        $name = 'Woocommerce';
        $download = Plugin::where('name', 'Woocommerce')->get()->sum('downloaded');
        $active_install = Plugin::where('name', 'Woocommerce')->get()->sum('active_installed');
       // dd($total_downloads);
        // try {
        //     if (!Cache::has('plugin_data')) {
        //         $response = $client->request('POST', 'https://api.wordpress.org/stats/plugin/1.0/downloads.php?slug=contact-form-7&limit=30');
        //         dd(json_decode((string) $response->getBody(), true));
        //         $data = json_decode((string) $response->getBody(), true);
    
        //         Cache::put('plugin_data', $data, Carbon::now()->endOfDay());
        //     }
        // } catch (ClientException $e) {
        //     throw new Exception($e->getMessage());
        // }

     

        return view('welcome', compact('name', 'download', 'active_install'));
    }

    public function filter(Request $request)
    {
        $name = $request->filter_by;
        $download = Plugin::where('name', $request->filter_by)
                    ->whereBetween('date', [$request->from_date, $request->to_date])
                    ->get()->sum('downloaded');

        $active_install = Plugin::where('name', $request->filter_by)
                    ->whereBetween('date', [$request->from_date, $request->to_date])
                    ->get()->sum('active_installed');
        
        return view('welcome', compact('name', 'download', 'active_install'));
    }
}
