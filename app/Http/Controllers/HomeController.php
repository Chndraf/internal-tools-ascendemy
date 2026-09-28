<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Email;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\Browsershot\Browsershot;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            // 1. LOGIKA MODE KEYWORD
            if ($request->type === 'keyword') {
                $query = Email::with(['country', 'keyword']);

                if ($request->country && $request->country !== 'all') {
                    $query->where('country_id', $request->country);
                }
                if ($request->keyword && $request->keyword !== 'all') {
                    $query->where('keyword_id', $request->keyword);
                }

                $emails = $query->paginate(100);

                return response()->json($emails);
            }

            // 2. LOGIKA MODE URL
            if ($request->type === 'url') {
                $url = $request->url_endpoint;

                if (! $url || ! filter_var($url, FILTER_VALIDATE_URL)) {
                    return response()->json(['data' => [], 'total' => 0]);
                }

                try {
                    // Menggunakan Browsershot pengganti Http::get
                    $html = Browsershot::url($url)
                        ->noSandbox()
                        ->ignoreHttpsErrors() // Mengabaikan error SSL di localhost
                        ->waitUntilNetworkIdle() // KUNCI UTAMA: Menunggu JS selesai loading data
                        ->bodyHtml();

                    if ($html) {
                        // Regex untuk ekstrak email
                        $pattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/i';
                        preg_match_all($pattern, $html, $matches);

                        if (! empty($matches[0])) {
                            $uniqueEmails = array_unique($matches[0]);
                            $cleanEmails = array_filter($uniqueEmails, function ($email) {
                                return ! preg_match('/\.(png|jpg|jpeg|gif|css|js|svg|webp)$/i', $email);
                            });

                            $finalEmails = array_values($cleanEmails);

                            return response()->json([
                                'data' => $finalEmails,
                                'total' => count($finalEmails),
                            ]);
                        }
                    }

                    return response()->json(['data' => [], 'total' => 0]);

                } catch (\Exception $e) {
                    // Tampilkan pesan error asli dari sistem ke dalam tabel
                    return response()->json([
                        'data' => ['(ERROR SISTEM) '.$e->getMessage()],
                        'total' => 1,
                    ]);
                }
            }
        }

        $countries = Country::orderBy('name', 'asc')->get();
        $keywords = Keyword::orderBy('name', 'asc')->get();

        return view('mail-extractor', compact('countries', 'keywords'));
    }
}
